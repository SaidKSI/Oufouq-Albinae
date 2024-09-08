<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Product;
use App\Models\Project;
use App\Models\TransportationExpenses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{

    function index()
    {
        $projects = Project::all();
        $expenses = Expense::select(
            'ref',
            DB::raw('MAX(id) as id'),
            DB::raw('MAX(name) as name'),
            DB::raw('MAX(total_amount) as total_amount'),
            DB::raw('MAX(project_id) as project_id'),
            DB::raw('MAX(type) as type'),
            DB::raw('MAX(amount) as amount'),
            DB::raw('MAX(description) as description'),
            DB::raw('MAX(start_date) as start_date'),
            DB::raw('MAX(repeat_interval) as repeat_interval')
        )
            ->groupBy('ref')
            ->get();

        return view('expense.index', compact('projects', 'expenses'));
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
    private function createRepeatedExpenses(Request $request, $project_id)
    {
        $startDate = Carbon::parse($request->start_date);
        $repeatInterval = $request->repeat_interval;
        $duration = $request->duration;
        $ref = 'EXP' . rand(1000, 9999);
        for ($i = 0; $i < $repeatInterval; $i++) {
            Expense::create([
                'name' => $request->name,
                'project_id' => $project_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'description' => $request->description,
                'start_date' => $startDate->copy()->toDateString(),
                'repeat_interval' => $repeatInterval,
                'ref' => $ref,
                'total_amount' => $request->amount * $repeatInterval,
            ]);

            switch ($duration) {
                case 'daily':
                    $startDate->addDay();
                    break;
                case 'weekly':
                    $startDate->addWeek();
                    break;
                case 'monthly':
                    $startDate->addMonth();
                    break;
                case 'yearly':
                    $startDate->addYear();
                    break;
            }
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'target' => 'required|string',
            'type' => 'required|string|in:fix,variable',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'repeat_interval' => 'nullable|integer|min:1',
            'duration' => 'nullable|string|in:daily,weekly,monthly,yearly',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }
        $project_id = $request->target === 'company' ? null : $request->target;

        if ($request->type === 'fix') {
            $this->createRepeatedExpenses($request, $project_id);
        } else {
            Expense::create([
                'name' => $request->name,
                'project_id' => $project_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'ref' => 'EXP' . rand(1000, 9999),
                'total_amount' => $request->amount,
            ]);
        }

        return redirect()->back()->with('success', 'Expense added successfully.');
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

    function destroy($id)
    {

        $expense = Expense::find($id);
        // delete all expenses with the same ref
        if ($expense) {
            $expense->delete();
            Expense::where('ref', $expense->ref)->delete();

            return redirect()->back()->with('success', 'Expense deleted successfully.');
        }
        return redirect()->back()->with('error', 'Expense not found.');
    }
}