@extends('layouts.app')
@section('title', 'Shifts')
@section('content')
<x-Breadcrumb title="Shifts" />
<div class="row">
    <div class="card">
        <div class="col-md-2 mx-3 my-2">


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
        </div>
        <div class="card-body p-2">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Empoyees</th>
                        <th>Action</th>
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
                    <tr>
                        <td rowspan="4">{{ $currentMonth }}</td>
                        <td>{{ $shift->name }}</td>
                        <td>{{ $shift->date_begin }}</td>
                        <td>{{ $shift->date_end }}</td>
                        <td>
                            <i class="ri-team-line"></i>
                        </td>
                        <td>
                            <i class="ri-user-add-fill text-success" data-bs-toggle="modal"
                                data-bs-target="#assignEmployeeModal" style="cursor: pointer;"></i>
                            <!-- Modal for assigning employees to shifts -->
                            <div class="modal fade" id="assignEmployeeModal" tabindex="-1"
                                aria-labelledby="assignEmployeeModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="assignEmployeeModalLabel">Assign Employee to
                                                Shift</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('shifts.assignUsers') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="shift_id">Select Shift</label>
                                                    <select name="shift_id" id="shift_id" class="form-control">
                                                        @foreach ($shifts as $shift)
                                                        <option value="{{ $shift->id }}">{{ $shift->name }} ({{
                                                            $shift->date_begin }} - {{ $shift->date_end }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="employer_id">Select Employee</label>
                                                    <select name="employer_id" id="employer_id"
                                                        class="form-control select2">
                                                        @foreach ($employers as $employer)
                                                        <option value="{{ $employer->id }}">{{ $employer->full_name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Assign</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @else
                    <tr>
                        @if ($shiftCount <= 4) <td>{{ $shift->name }}</td>
                            <td>{{ $shift->date_begin }}</td>
                            <td>{{ $shift->date_end }}</td>
                            <td>
                                <i class="ri-team-line"></i>
                            </td>
                            <td>
                                <i class="ri-user-add-fill text-success" data-bs-toggle="modal"
                                    data-bs-target="#assignEmployeeModal" style="cursor: pointer;"></i>
                                <!-- Modal for assigning employees to shifts -->
                                <div class="modal fade" id="assignEmployeeModal" tabindex="-1"
                                    aria-labelledby="assignEmployeeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="assignEmployeeModalLabel">Assign Employee to
                                                    Shift</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('shifts.assignUsers') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="shift_id">Select Shift</label>
                                                        <select name="shift_id" id="shift_id" class="form-control">
                                                            @foreach ($shifts as $shift)
                                                            <option value="{{ $shift->id }}">{{ $shift->name }} ({{
                                                                $shift->date_begin }} - {{ $shift->date_end }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="employer_id">Select Employee</label>
                                                        <select name="employer_id" id="employer_id"
                                                            class="form-control select2">
                                                            @foreach ($employers as $employer)
                                                            <option value="{{ $employer->id }}">{{ $employer->full_name
                                                                }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Assign</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
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
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush