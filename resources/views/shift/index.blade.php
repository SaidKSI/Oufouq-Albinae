@extends('layouts.app')
@section('title', 'Shifts')
@section('content')
<x-Breadcrumb title="Shifts" />
<div class="row">
    <div class="card">
        <div class="col-md-4 mx-3 my-2 d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#generateWeeklyShiftsModal">
                Generate Next 4 Weekly Shifts
            </button>

            <!-- Modal for generating weekly shifts -->
            <div class="modal fade" id="generateWeeklyShiftsModal" tabindex="-1"
                aria-labelledby="generateWeeklyShiftsModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="generateWeeklyShiftsModalLabel">Generate Next 4 Weekly Shifts
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="generateWeeklyShiftsForm" action="{{ route('shifts.generateWeekly') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <p>Are you sure you want to generate the next 4 weekly shifts?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Generate</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#assignEmployeeModal"><i
                    class="ri-user-add-fill pointer"></i> Assign Employee to Shift</button>
            <!-- Modal for assigning employees to shifts -->
            <div class="modal fade" id="assignEmployeeModal" tabindex="-1" aria-labelledby="assignEmployeeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-full-width modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="assignEmployeeModalLabel">Assign Employee to
                                Shift</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                        @foreach ($employers as $employer)
                                        <option value="{{ $employer->id }}">{{ $employer->full_name }}</option>
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
        <div class="card-body p-2">
            @php
            $currentMonthName = \Carbon\Carbon::now()->format('F');
            @endphp

            <table class="table text-black table-bordered">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Shift Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if($shifts->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center">No shifts found</td>
                    </tr>
                    @endif
                    @php
                    $currentMonth = null;
                    $shiftCount = 0;
                    @endphp
                    @foreach ($shifts as $shift)
                    @php
                    $month = date('F', strtotime($shift->date_begin));
                    $shiftCount++;
                    @endphp
                    @if ($currentMonth !== $month)
                    @php
                    $currentMonth = $month;
                    $shiftCount = 1;
                    @endphp
                    <tr class="{{ $currentMonth == $currentMonthName ? 'bg-success' : '' }}">
                        <td rowspan="4">{{ $currentMonth }}</td>
                        <td>{{ $shift->name }}</td>
                        <td>{{ $shift->date_begin }}</td>
                        <td>{{ $shift->date_end }}</td>
                    </tr>
                    @else
                    <tr class="{{ $currentMonth == $currentMonthName ? 'bg-success' : '' }}">
                        @if ($shiftCount <= 4) <td>{{ $shift->name }}</td>
                            <td>{{ $shift->date_begin }}</td>
                            <td>{{ $shift->date_end }}</td>
                            @endif
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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