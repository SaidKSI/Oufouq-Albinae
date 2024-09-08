@extends('layouts.app')
@section('title', 'Shift Overview')
@section('content')
<x-Breadcrumb title="Shift Overview" />
<div class="row">
    <div class="card">
        <div class="col m-2">
            <ul class="pagination pagination-lg justify-content-end">
                <li class="page-item">
                    <a class="page-link"
                        href="{{ route('shifts.previousWeek', ['start_date' => $startDate ?? Carbon\Carbon::now()->startOfWeek()->toDateString()]) }}"
                        aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link"
                        href="{{ route('shifts.nextWeek', ['start_date' => $startDate ?? Carbon\Carbon::now()->startOfWeek()->toDateString()]) }}"
                        aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="col my-4 mx-2">
            <div class="table-responsive-sm ">
                @if($currentShift)
                <h2 class="text-center my-2">Weekly Shift: {{ $currentShift->name }} ({{$currentShift->date_begin}} to {{$currentShift->date_end}}) </h2>
                <table class="table table-bordered table-centered mb-0">
                    <thead>
                        <tr>
                            <th>Days</th>
                            @for ($date = \Carbon\Carbon::parse($currentShift->date_begin);
                            $date->lte(\Carbon\Carbon::parse($currentShift->date_end)); $date->addDay())
                            <th>{{ $date->format('l') }}<br>{{ $date->format('d/m/Y') }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($employees as $employee)
                        <tr>
                            <td>{{ $employee->full_name }}</td>
                            @for ($date = \Carbon\Carbon::parse($currentShift->date_begin);
                            $date->lte(\Carbon\Carbon::parse($currentShift->date_end)); $date->addDay())
                            @php
                            $attendanceKey = $employee->id . '-' . $date->format('Y-m-d');
                            $hoursWorked = isset($attendances[$attendanceKey]) ?
                            $attendances[$attendanceKey]->hours_worked : '';
                            @endphp
                            <td class="p-0">
                                <div
                                    class="d-flex flex-column justify-content-center align-items-center text-{{$hoursWorked > 0 ? 'success' : 'danger'}} w-100 h-100">
                                    {{ $hoursWorked }}
                                </div>
                            </td>
                            @endfor
                        </tr>
                        @endforeach
                        </tr>
                    </tbody>
                </table>
                @else
                <p>No current weekly shift available.</p>
                <a href="{{route('shift.index')}}">Shifts <i class="ri-picture-in-picture-exit-fill"></i></a>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection