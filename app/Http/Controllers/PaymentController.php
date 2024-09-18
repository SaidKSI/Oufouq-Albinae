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
use App\Models\Project;

class PaymentController extends Controller
{
    function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'paid_price' => 'required|numeric',
            'payment_method' => 'required|string',
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }
        $order = Order::findOrFail($request->order_id);
        // check if the paid price is greater than the remaining amount
        if ($order->total_price - $order->payments->sum('paid_price') < $request->paid_price) {
            // toastr()->error('Paid price is greater than the remaining amount.' . ($order->total_price - $order->payments->sum('paid_price')));
            return back()->with('error', 'Paid price is greater than the remaining amount.' . ($order->total_price - $order->payments->sum('paid_price')));
        }
        $remaining = $order->total_price - $order->payments->sum('paid_price') - $request->paid_price;
        $order->remaining = $remaining;
        $order->paid_amount = $order->total_price - $remaining;
        $order->payment_status = $remaining <= 0 ? 'paid' : 'pending';
        $order->save();

        $payment = new Payment();
        $payment->order_id = $request->order_id;
        $payment->paid_price = $request->paid_price;
        $payment->remaining = $remaining;
        $payment->payment_method = $request->payment_method;
        $payment->payment_id = 'PMT' . rand(1000, 9999);
        $payment->date = now()->toDateString();
        $payment->type = 'order';
        $payment->save();

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $extension = $file->getClientOriginalExtension();
            $fileName = $payment->payment_id . '-' . 'N°' . $order->Ref . '.' . $extension;
            $path = $file->storeAs('payment-documents', $fileName, 'public');
            // dd($path);pDoc
            $payment->documents()->create([
                'path' => $path,
            ]);
        }
        event(new OrderPaid($request->paid_price));

        return redirect()->route('order.index')->with('success', 'Payment added successfully.');
    }
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
        $projects = Project::whereHas('estimate', function ($query) {
            $query->where('type', 'estimate');
        })->whereDoesntHave('estimate', function ($query) {
            $query->where('type', 'invoice');
        })->get();
        $invoices = Estimate::where('type', 'invoice')->get();
        return view('estimate.payment', ['projects' => $projects,'invoices' => $invoices]);
    }

    function storeEstimatePayment(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }
        $oldEstimate = Estimate::where('project_id', $request->project_id)->first();
        $estimate = new Estimate();
        $estimate->project_id = $request->project_id;
        $estimate->reference = $oldEstimate->reference;
        $estimate->quantity = $oldEstimate->quantity;
        $estimate->total_price = $oldEstimate->total_price;
        $estimate->tax = $oldEstimate->tax;
        $estimate->type = 'invoice';
        $estimate->number = 'INV-' . rand(1000, 9999);
        $estimate->payment_method = $request->payment_method;
        $estimate->save();

        return redirect()->back()->with('success', 'Payment added successfully.');
    }

}