<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    function index()
    {
        $suppliers = Supplier::with(['delivery', 'delivery.bills'])->get();
        $supplierCount = $suppliers->count();
        return view('supplier.index', compact('suppliers', 'supplierCount'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'city' => 'required|string',
            'description' => 'required|string',
            'ice' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors('error', $validator->errors());
        }


        $supplier = new Supplier();
        $supplier->full_name = $request->full_name;
        $supplier->phone = $request->phone;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->city = $request->city;
        $supplier->description = $request->description;
        $supplier->ice = $request->ice;
        $supplier->facebook_handle = $request->facebook;
        $supplier->instagram_handle = $request->instagram;
        $supplier->twitter_handle = $request->twitter;
        $supplier->linkedin_handle = $request->linkedin;
        $supplier->save();

        // toastr()->success('Supplier added successfully.');
        return redirect()->route('supplier.index')->with('success', 'Supplier added successfully.');
    }
    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);

        $deliveriesQuery = $supplier->delivery();

        // Apply date filters if present
        if (request('start_date')) {
            $deliveriesQuery->whereDate('created_at', '>=', request('start_date'));
        }
        if (request('end_date')) {
            $deliveriesQuery->whereDate('created_at', '<=', request('end_date'));
        }

        $deliveries = $deliveriesQuery->orderBy('created_at', 'desc')->get();

        return view('supplier.show', compact('supplier', 'deliveries'));
    }
    function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'description' => 'nullable|string',
            'ice' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors('error', $validator->errors());
        }

        $supplier = Supplier::findOrFail($id);
        $supplier->full_name = $request->full_name;
        $supplier->phone = $request->phone;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->city = $request->city;
        $supplier->description = $request->description;
        $supplier->ice = $request->ice;
        $supplier->facebook_handle = $request->facebook;
        $supplier->instagram_handle = $request->instagram;
        $supplier->twitter_handle = $request->twitter;
        $supplier->linkedin_handle = $request->linkedin;
        $supplier->save();

        return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully.');
    }
    function destroy($id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Supplier deleted successfully.');
    }
    function purchase()
    {
        return view('supplier.purchase');
    }
}