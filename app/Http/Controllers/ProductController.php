<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    function index() {
        $products =Product::all();
        return view('product.index', compact('products'));
    }
    public function getProducts()
    {
        $products = Product::all(['id', 'name']);
        return response()->json($products);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $products = Product::where('name', 'LIKE', "%{$query}%")->get(['id', 'name', 'unit']);
        return response()->json($products);
    }

    function productStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'unit'=> 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }

       Product::create([
            'name' => $request->name,
            'unit' => $request->unit,
        ]);

        return redirect()->back()->with('success', 'Product Created successfully.');
    }
}