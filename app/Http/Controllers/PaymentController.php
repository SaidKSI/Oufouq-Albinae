<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'paid_price' => 'required|numeric',
            'payment_method' => 'required|string',
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpeg,png,jpg,gif|max:2048',
        ]);

        $order = Order::findOrFail($request->order_id);
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
        $payment->save();
        
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $extension = $file->getClientOriginalExtension();
            $fileName =  'N°'.$order->Ref . '.' . $extension;
            $path = $file->storeAs('payment-documents', $fileName, 'public');
            // dd($path);pDoc
            $payment->documents()->create([
                'path' => $path,
            ]);
        }
        return redirect()->route('order.index')->with('success', 'Payment added successfully.');
    }
}