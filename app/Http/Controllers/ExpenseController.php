<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Project;
use App\Models\TransportationExpenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{

    function variable()
    {
        return view('expense.variable');
    }
    function transportation()
    {
        $expenses = TransportationExpenses::all();
        $projects = Project::all();
        $products = Product::whereHas('orderItems', function ($query) {
            $query->where('status', 'delivered');
        })->get();
        return view('expense.transportation', [
            'projects' => $projects,
            'products' => $products,
            'expenses' => $expenses
        ]);
    }

    function transportationStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'highway_expense' => 'required|numeric',
            'gaz_expense' => 'required|numeric',
            'other_expense' => 'required|numeric',
            'description' => 'nullable|string',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }
        $total_expense = $request->highway_expense + $request->other_expense;

        $expense = new TransportationExpenses();
        $expense->project_id = $request->project_id;
        $expense->product_id = $request->product_id;
        $expense->quantity = $request->quantity;
        $expense->highway_expense = $request->highway_expense;
        $expense->gaz_expense = $request->gaz_expense;
        $expense->other_expense = $request->other_expense;
        $expense->total_expense = $total_expense;
        $expense->description = $request->description;
        $expense->ref = 'TRN' . rand(1000, 9999);   
        $expense->save();

        return redirect()->back()->with('success', 'Transportation Expense Added Successfully');
        
    }
}