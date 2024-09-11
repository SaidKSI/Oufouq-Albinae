<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
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
        return view('order.index', ['orders' => $orders, 'orderCount' => $orderCount, 'projects' => $projects, 'suppliers' => $suppliers]);
    }

    function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'description' => 'nullable|string',
            'order_items' => 'required|array',
            'order_items.*.product_id' => 'nullable|exists:products,id',
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
            'status' => 'pending',
            'description' => $request->description,
        ]);

        foreach ($request->order_items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price_unit' => $item['price_unit'],
                'total_price' => $item['total_price'],
                'status' => 'pending',
            ]);
        }
        $order->updateTotalPrice();
        $order->updateRemaining();
        // dd($order->remaining);
        return redirect()->route('order.index')->with('success', 'Order created successfully.');
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'description' => 'nullable|string',
            'order_items' => 'required|array',
            'order_items.*.id' => 'nullable|exists:order_items,id',
            'order_items.*.product_id' => 'nullable|exists:products,id',
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

        $order = Order::findOrFail($id);
        $order->update([
            'project_id' => $request->project_id,
            'supplier_id' => $request->supplier_id,
            'description' => $request->description,
        ]);

        // Compare the order items and the updated order items
        $orderItems = $order->items;
        foreach ($orderItems as $item) {
            $found = false;
            foreach ($request->order_items as $updatedItem) {
                if (isset($updatedItem['id']) && $item->id == $updatedItem['id']) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $item->delete();
            }
        }

        foreach ($request->order_items as $item) {
            if (isset($item['id'])) {
                $orderItem = OrderItem::findOrFail($item['id']);
                $orderItem->update([
                    'quantity' => $item['quantity'],
                    'product_id' => $item['product_id'],
                    'price_unit' => $item['price_unit'],
                    'total_price' => $item['total_price'],
                ]);
            } else {
                OrderItem::create([
                    'order_id' => $order->id,
                    'quantity' => $item['quantity'],
                    'product_id' => $item['product_id'],
                    'price_unit' => $item['price_unit'],
                    'total_price' => $item['total_price'],
                ]);
            }
        }

        $order->updateTotalPrice();
        $order->updateRemaining();

        return redirect()->route('order.index')->with('success', 'Order updated successfully.');
    }

    function edit($id)
    {
        $order = Order::findOrFail($id);
        $projects = Project::all();
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('order.edit', ['order' => $order, 'projects' => $projects, 'suppliers' => $suppliers, 'products' => $products]);

    }

    function show($id)
    {
        $order = Order::findOrFail($id);
        return view('order.show', ['order' => $order]);
    }

    function changeStatus(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:pending,processing,completed',  
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }
        
        $order = Order::findOrFail($id);
        //check if the order remaining amount is greater than 0
        if ($order->remaining > 0) {
            // toastr()->error('order not fully paid yet.');
            return back()->with('error', 'order not fully paid yet.');
        }
        $order->status = $request->status;
        $order->due_date = today()->format('Y-m-d');
        $order->save();
        // change all order item status to delivered

        foreach ($order->items as $item) {
            $item->status = 'delivered';
            $item->save();
        }
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $extension = $file->getClientOriginalExtension();
            $fileName =  'N°'.$order->Ref . '.' . $extension;
            $path = $file->storeAs('order-documents', $fileName, 'public');
            $order->documents()->create([
                'path' => $path,
            ]);
        }

        return redirect()->route('order.index')->with('success', 'Order status changed successfully.');
    }

}