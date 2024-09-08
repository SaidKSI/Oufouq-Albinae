@extends('layouts.app')
@section('title', 'Shift Attendance')
@section('content')
<x-Breadcrumb title="Shift Attendance" />
<div class="row">
    <div class="card">
        <div class="card-body">
            <div class="col-md-5 mb-3">
                <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#assignEmployeeModal"><i
                        class="ri-user-add-fill pointer"></i> Assign Employee to Shift</button>

                <!-- Modal for assigning employees to shifts -->
                <div class="modal fade" id="assignEmployeeModal" tabindex="-1"
                    aria-labelledby="assignEmployeeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-full-width modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="assignEmployeeModalLabel">Assign Employee to
                                    Shift</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('shift.assignUsers') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group mb-2">
                                        <label for="shift_id">Select Shift</label>
                                        <select name="shift_id" id="shift_id" class="form-control">
                                            <option disabled selected>Select a Shift</option>
                                            @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}">{{ $shift->name }} ({{
                                                $shift->date_begin }} - {{ $shift->date_end }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="employer_id">Select Employees</label>
                                        <select name="employer_ids[]" id="employer_id"
                                            class="select2 form-control select2-multiple" data-toggle="select2"
                                            multiple="multiple" data-placeholder="Choose ...">
                                            @foreach ($workers as $worker)
                                            <option value="{{ $worker->id }}">{{ $worker->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <table id="basic-datatable" class="table table-striped dt-responsive w-100">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Full Name</th>
                                                <th>Phone</th>
                                                <th>City</th>
                                                <th>Address</th>
                                                <th>Profession</th>
                                                <th>CNSS</th>
                                                <th>Wage Per Hour</th>
                                            </tr>
                                        </thead>
                                        <tbody id="employeesTableBody">
                                            <!-- Employees will be loaded here via AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Assign</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive-sm">
                    <form id="attendance-form" action="{{ route('shift.mark-attendance') }}" method="POST">
                        @csrf
                        @if($currentShift)
                        <h2>Current Weekly Shift: {{ $currentShift->name }}</h2>
                        <table class="table table-centered mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Days</th>
                                    @for ($date = \Carbon\Carbon::parse($currentShift->date_begin);
                                    $date->lte(\Carbon\Carbon::parse($currentShift->date_end)); $date->addDay())
                                    <th>{{ $date->format('l') }}<br>{{ $date->format('d/m/Y') }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                <tr>
                                    <td>{{ $employee->full_name }}</td>
                                    @for ($date = \Carbon\Carbon::parse($currentShift->date_begin);
                                    $date->lte(\Carbon\Carbon::parse($currentShift->date_end)); $date->addDay())
                                    <td>
                                        @php
                                        $attendanceKey = $employee->id . '-' . $date->format('Y-m-d');
                                        $hoursWorked = isset($attendances[$attendanceKey]) ?
                                        $attendances[$attendanceKey]->hours_worked : '';
                                        @endphp
                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                            <input type="number" class="form-control attendance-hours my-1 border-0"
                                                name="attendance[{{ $employee->id }}][{{ $date->format('Y-m-d') }}][hours]"
                                                placeholder="Hours Worked" min="0" step="0.5"
                                                value="{{ $hoursWorked }}">
                                        </div>
                                    </td>
                                    @endfor
                                </tr>
                                @endforeach
                                <tr>
                                    <td>Save</td>
                                    @for ($date = \Carbon\Carbon::parse($currentShift->date_begin);
                                    $date->lte(\Carbon\Carbon::parse($currentShift->date_end)); $date->addDay())
                                    <td>
                                        <button type="button" class="btn btn-primary save-column"
                                            data-date="{{ $date->format('Y-m-d') }}" >Save</button>
                                    </td>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                        @else
                        <p>No current weekly shift available.</p>
                        <a href="{{route('shift.index')}}">Shifts <i class="ri-picture-in-picture-exit-fill"></i></a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const saveButtons = document.querySelectorAll('.save-column');
        saveButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const date = this.getAttribute('data-date');
                const form = document.getElementById('attendance-form');
                if (!form) {
                    console.error('Form element not found');
                    return;
                }
                const formData = new FormData(form);

                const data = {
                    _token: '{{ csrf_token() }}',
                    date: date,
                    attendance: {}
                };

                formData.forEach((value, key) => {
                    if (key.includes(`[${date}]`)) {
                        const matches = key.match(/attendance\[(\d+)\]\[([^\]]+)\]\[([^\]]+)\]/);
                        if (matches) {
                            const employeeId = matches[1];
                            const field = matches[3];
                            if (!data.attendance[employeeId]) {
                                data.attendance[employeeId] = { employer_id: employeeId, date: date };
                            }
                            data.attendance[employeeId][field] = value;
                        }
                    }
                });

                $.ajax({
                    url: '{{ route("shift.mark-attendance") }}',
                    method: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    success: function(response) {
                        alert('Attendance marked successfully');
                        console.log('Attendance marked successfully');
                    },
                    error: function(xhr, status, error) {
                        alert('Error marking attendance');
                        console.error('Error marking attendance:', error);
                    }
                });
            });
        });
    });
</script>
<!-- Initialize Select2 and handle AJAX request -->
<script>
    $(document).ready(function() {
        $('.select2').select2();

        $('#shift_id').change(function() {
            var shiftId = $(this).val();
            $.ajax({
                url: '/dashboard/shifts/' + shiftId + '/employees',
                method: 'GET',
                success: function(data) {
                    var employeesTableBody = $('#employeesTableBody');
                    employeesTableBody.empty();
                    if (data.length > 0) {
                        data.forEach(function(employee, index) {
                            employeesTableBody.append(
                                '<tr>' +
                                    '<td>' + (index + 1) + '</td>' +
                                    '<td>' + employee.full_name + '</td>' +
                                    '<td>' + employee.phone + '</td>' +
                                    '<td>' + employee.city + '</td>' +
                                    '<td>' + employee.address + '</td>' +
                                    '<td>' + employee.profession.name + '</td>' +
                                    '<td>' + (employee.cnss ? '<i class="bi bi-check text-success fs-3"></i>' : '<i class="bi bi-x text-danger fs-3"></i>') + '</td>' +
                                    '<td>' + employee.wage_per_hr + '</td>' +
                                '</tr>'
                            );
                        });
                    } else {
                        employeesTableBody.append('<tr><td colspan="8">No employees assigned to this shift.</td></tr>');
                    }
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#assignEmployeeModal').on('shown.bs.modal', function () {
            $('.select2').select2({
                dropdownParent: $('#assignEmployeeModal'),
                allowClear: true
            });
        });
    });
</script>
@endpush