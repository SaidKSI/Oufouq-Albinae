<?php

namespace App\Http\Controllers;

use App\Events\EmployerPaid;
use App\Models\Attendance;
use App\Models\Employer;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\OrderPaid;
use App\Models\Estimate;
use App\Models\Facture;
use App\Models\Project;

class PaymentController extends Controller
{

    function payment()
    {
        $employees = Employer::all();
        $payments = Payment::where('type', 'employer')->get();
        return view('employer.payment', ['employees' => $employees, 'payments' => $payments]);
    }
    function employer_payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employers,id',
            'paid_price' => 'required|numeric|min:0',
            'payment_method' => 'required|string'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }

        $employee = Employer::findOrFail($request->employee_id, );
        $totalWage = Attendance::where('employer_id', $request->employee_id, )
            ->sum('hours_worked') * $employee->wage_per_hr;

        $totalWage -= Payment::where('employee_id', $request->employee_id, )
            ->sum('paid_price');
        $remaining = $totalWage - number_format((float) $request->paid_price, 2, '.', '');
        // dd($remaining , $totalWage , $request->paid_price);
        if ($totalWage < $request->paid_price) {
            return back()->with('error', 'Amount is greater than the total wage.');
        }


        Payment::create([
            'employee_id' => $request->employee_id,
            'paid_price' => $request->paid_price,
            'remaining' => $remaining,
            'payment_id' => 'PMT' . rand(100, 999),
            'payment_method' => $request->payment_method,
            'date' => now()->toDateString(),
            'type' => 'employer',
        ]);
        event(new EmployerPaid($request->paid_price));

        return redirect()->back()->with('success', 'Payment added successfully.');
    }

    public function getTotalWage($employeeId)
    {
        $employee = Employer::findOrFail($employeeId);
        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }
        $totalWage = Attendance::where('employer_id', $employeeId)
            ->sum('hours_worked') * $employee->wage_per_hr;
        $total_paid = Payment::where('employee_id', $employeeId)
            ->sum('paid_price');
        $totalWage -= $total_paid;
        return response()->json(['total_wage' => round($totalWage)]);
    }
    function invoice($id)
    {
        $payment = Payment::findOrFail($id);
        return view('employer.invoice', ['payment' => $payment]);
    }
    function estimatePayment()
    {
        $invoices = Facture::get();
        return view('estimate.payment', ['invoices' => $invoices]);
    }


}