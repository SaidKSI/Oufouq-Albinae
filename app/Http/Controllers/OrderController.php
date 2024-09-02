<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Project;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    function index()
    {
        $projects = Project::all();
        $suppliers = Supplier::all();
        $orders = Order::all();
        $orderCount = $orders->count();
        return view('order.index', ['orders' => $orders, 'orderCount' => $orderCount,'projects' => $projects,'suppliers' => $suppliers]);
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'due_date' => 'required|date',
            'description' => 'nullable|string',
            'order_items' => 'required|array',
            'order_items.*.name' => 'required|string',
            'order_items.*.unit' => 'required|string',
            'order_items.*.quantity' => 'required|integer',
            'order_items.*.price_unit' => 'required|numeric',
            'order_items.*.total_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }

        $ref = rand(100000, 999999);
        $order = Order::create([
            'project_id' => $request->project_id,
            'supplier_id' => $request->supplier_id,
            'Ref' => $ref,
            'paid_amount' => 0,
            'remaining' => $request->total_price,
            'status' => 'pending',
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        foreach ($request->order_items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'name' => $item['name'],
                'unit' => $item['unit'],
                'quantity' => $item['quantity'],
                'price_unit' => $item['price_unit'],
                'total_price' => $item['total_price'],
                'status' => 'pending',
            ]);
        }

        return redirect()->route('order.index')->with('success', 'Order created successfully.');
    }
}