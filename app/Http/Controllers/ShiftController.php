<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employer;
use App\Models\EmployerShift;
use App\Models\Shift;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::whereYear('date_begin', Carbon::now()->year)->get();
        $employers = Employer::all();
        return view('shift.index', ['shifts' => $shifts, 'employers' => $employers]);
    }
    public function generateWeeklyShifts()
    {
        // Get the last shift or use current date if no shifts exist
        $lastShift = Shift::orderBy('date_end', 'desc')->first();
        $startDate = $lastShift
            ? Carbon::parse($lastShift->date_end)->addDay()->startOfWeek()
            : Carbon::now()->startOfWeek();

        $endDate = $startDate->copy()->endOfWeek();
        $weeksToGenerate = 4;
        $shiftsGenerated = 0;

        for ($i = 0; $i < $weeksToGenerate; $i++) {
            // Check if a shift already exists for this week
            $existingShift = Shift::where('date_begin', $startDate->toDateString())
                ->where('date_end', $endDate->toDateString())
                ->first();

            if (!$existingShift) {
                Shift::create([
                    'name' => $startDate->format('F') . '-Shift-' . $startDate->weekOfYear,
                    'date_begin' => $startDate->toDateString(),
                    'date_end' => $endDate->toDateString(),
                ]);
                $shiftsGenerated++;
            }

            // Move to the next week
            $startDate->addWeek();
            $endDate->addWeek();
        }

        if ($shiftsGenerated > 0) {
            return redirect()->back()->with('success', $shiftsGenerated . ' new shifts generated successfully.');
        } else {
            return redirect()->back()->with('info', 'No new shifts were needed. Shifts already exist for the next 4 weeks.');
        }
    }
    public function assignUsers(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'employer_ids' => 'required|array',
            'employer_ids.*' => 'exists:employers,id',
        ]);

        foreach ($request->employer_ids as $employer_id) {
            EmployerShift::create([
                'shift_id' => $request->shift_id,
                'employer_id' => $employer_id,
                'is_present' => false,
                'hours_worked' => 0,
                'total_wage' => 0,
            ]);
        }

        return redirect()->back()->with('success', 'Employers assigned to shift successfully.');
    }

    public function getEmployees($shiftId)
    {
        $shift = Shift::with('employerShifts.employer.profession')->findOrFail($shiftId);
        $employees = $shift->employerShifts->map(function ($employerShift) {
            return $employerShift->employer;
        });

        return response()->json($employees);
    }

    public function attendance()
    {
        $startDate = Carbon::now()->startOfWeek(); // Start of the current week (Monday)
        $endDate = $startDate->copy()->endOfWeek(); // End of the current week (Sunday)

        $currentShift = Shift::where('date_begin', '<=', $startDate->toDateString())
            ->where('date_end', '>=', $endDate->toDateString())
            ->first();

        $shifts = Shift::all();
        $workers = Employer::all();
        if ($currentShift) {
            $employees = $currentShift->employerShifts->map(function ($employerShift) {
                return $employerShift->employer;
            });
        } else {
            $employees = collect(); // Empty collection if no current shift
        }
        if (!$currentShift) {
            return view('shift.attendance', compact('currentShift', 'employees', 'shifts', 'workers'));
        }
        $attendances = Attendance::where('shift_id', $currentShift->id)
            ->whereBetween('date', [$currentShift->date_begin, $currentShift->date_end])
            ->get()
            ->keyBy(function ($item) {
                return $item->employer_id . '-' . $item->date;
            });

        return view('shift.attendance', compact('currentShift', 'employees', 'shifts', 'workers', 'attendances'));
    }


    public function markAttendance(Request $request)
    {
        // Log the request data
        // Debugbar::info($request->all());

        // Validate the request data
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.employer_id' => 'required|exists:employers,id',
            'attendance.*.hours' => 'required|numeric|min:0',
        ]);

        // Find the shift for the given date
        $shift = Shift::where('date_begin', '<=', $request->date)
            ->where('date_end', '>=', $request->date)
            ->first();

        if (!$shift) {
            return response()->json(['success' => false, 'message' => 'Shift not found for the given date'], 404);
        }

        // Iterate over the attendance data and update or create attendance records
        foreach ($request->attendance as $attendanceData) {
            // Debugbar::info($attendanceData['employer_id']);
            Attendance::updateOrCreate(
                [
                    'shift_id' => $shift->id,
                    'employer_id' => $attendanceData['employer_id'],
                    'date' => $attendanceData['date'],
                ],
                [
                    'is_present' => $attendanceData['hours'] > 0,
                    'hours_worked' => $attendanceData['hours'],

                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Attendance marked successfully']);
    }

    function show()
    {
        $startDate = Carbon::now()->startOfWeek(); // Start of the current week (Monday)
        $endDate = $startDate->copy()->endOfWeek(); // End of the current week (Sunday)

        $currentShift = Shift::where('date_begin', '<=', $startDate->toDateString())
            ->where('date_end', '>=', $endDate->toDateString())
            ->first();

        $shifts = Shift::all();
        $workers = Employer::all();
        if ($currentShift) {
            $employees = $currentShift->employerShifts->map(function ($employerShift) {
                return $employerShift->employer;
            });
        } else {
            $employees = collect(); // Empty collection if no current shift
        }
        if (!$currentShift) {
            return view('shift.attendance', compact('currentShift', 'employees', 'shifts', 'workers'));
        }
        $attendances = Attendance::where('shift_id', $currentShift->id)
            ->whereBetween('date', [$currentShift->date_begin, $currentShift->date_end])
            ->get()
            ->keyBy(function ($item) {
                return $item->employer_id . '-' . $item->date;
            });
        return view('shift.overview', ['currentShift' => $currentShift, 'employees' => $employees, 'attendances' => $attendances]);
    }

    public function previousWeek(Request $request)
    {
        $startDate = Carbon::parse($request->query('start_date'))->subWeek();
        return $this->showShiftsForWeek($startDate);
    }

    public function nextWeek(Request $request)
    {
        $startDate = Carbon::parse($request->query('start_date'))->addWeek();
        return $this->showShiftsForWeek($startDate);
    }

    private function showShiftsForWeek($startDate)
    {
        $endDate = $startDate->copy()->endOfWeek();
        $currentShift = Shift::where('date_begin', '<=', $startDate->toDateString())
            ->where('date_end', '>=', $endDate->toDateString())
            ->first();

        $shifts = Shift::all();
        $workers = Employer::all();
        if ($currentShift) {
            $employees = $currentShift->employerShifts->map(function ($employerShift) {
                return $employerShift->employer;
            });
        } else {
            $employees = collect(); // Empty collection if no current shift
        }
        if (!$currentShift) {
            return view('shift.attendance', compact('currentShift', 'employees', 'shifts', 'workers'));
        }
        $attendances = Attendance::where('shift_id', $currentShift->id)
            ->whereBetween('date', [$currentShift->date_begin, $currentShift->date_end])
            ->get()
            ->keyBy(function ($item) {
                return $item->employer_id . '-' . $item->date;
            });

        return view('shift.overview', [
            'currentShift' => $currentShift,
            'employees' => $employees,
            'attendances' => $attendances,
            'startDate' => $startDate->toDateString(),
        ]);
    }
}