<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $payment->payment_id = 'PMT' . rand(100, 999);
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
        return redirect()->route('order.index')->with('success', 'Payment added successfully.');
    }
}