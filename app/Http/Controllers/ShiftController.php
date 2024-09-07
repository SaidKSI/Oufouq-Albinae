<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\EmployerShift;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{
    function index()
    {
        $shifts = Shift::all();
        $employers = Employer::all();
        return view('shift.index', ['shifts' => $shifts, 'employers' => $employers]);
    }
    public function generateWeeklyShifts(Request $request)
    {
        $startDate = Carbon::now()->startOfWeek(); // Start of the current week (Monday)
        $endDate = $startDate->copy()->endOfWeek(); // End of the current week (Sunday)
        $currentMonth = $startDate->format('Y-m');

        // Check if shifts for the current month already exist
        $existingShifts = Shift::whereYear('date_begin', $startDate->year)
            ->whereMonth('date_begin', $startDate->month)
            ->count();

        if ($existingShifts >= 4) {
            // generate 4 weekly shifts for the next month
            $startDate->addMonth();
            $endDate->addMonth();

        }
        $shiftCount = Shift::count();
        if ($shiftCount >= 8) {
            return redirect()->back()->with('error', 'You have already generated shifts for the next 2 month.');
        }
        for ($i = 0; $i < 4; $i++) {
            Shift::create([
                'name' => date('F', strtotime($startDate)) . '-Shift-' . ($i + 1),
                'date_begin' => $startDate->toDateString(),
                'date_end' => $endDate->toDateString(),
            ]);

            // Move to the next week
            $startDate->addWeek();
            $endDate->addWeek();
        }

        return redirect()->back()->with('success', 'Next 4 weekly shifts generated successfully.');
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
        $currentDate = Carbon::now();
        $currentShift = Shift::where('date_begin', '<=', $currentDate)
            ->where('date_end', '>=', $currentDate)
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
        
        return view('shift.attendance', compact('currentShift', 'employees','shifts','workers'));
    }

    function markAttendance(Request $request) {
        dd($request->all());
    }
}