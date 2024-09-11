<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Employer;
use App\Models\Expense;
use App\Models\Project;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    function index()
    {
        $projects = Project::all();
        $projectCount = $projects->count();
        $client = Client::all();
        return view('project.index', ['projects' => $projects, 'projectCount' => $projectCount, 'clients' => $client]);
    }

    function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'address' => 'required|string',
            'city' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'description' => 'required|string'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }
        $ref = 'PRJ-' . rand(1000, 9999);

        $project = new Project();
        $project->name = $request->name;
        $project->client_id = $request->client_id;
        $project->address = $request->address;
        $project->city = $request->city;
        $project->description = $request->description;
        $project->ref = $ref;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->save();

        return redirect()->back()->with('success', 'Project added successfully.');

    }

    function update(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'address' => 'required|string',
            'city' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'description' => 'required|string',
            'progress_percentage' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }

        $project = Project::find($request->id);
        $project->name = $request->name;
        $project->client_id = $request->client_id;
        $project->address = $request->address;
        $project->city = $request->city;
        $project->description = $request->description;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->progress_percentage = $request->progress_percentage;
        $project->save();


        return redirect()->back()->with('success', 'Project updated successfully.');
    }

    function destroy($id)
    {
        $project = Project::find($id);
        $project->delete();
        return redirect()->back()->with('success', 'Project deleted successfully.');
    }

    function show($id)
    {
        $project = Project::find($id);
        $expenses = Expense::select(
            'ref',
            DB::raw('MAX(id) as id'),
            DB::raw('MAX(name) as name'),
            DB::raw('MAX(total_amount) as total_amount'),
            DB::raw('MAX(type) as type'),
            DB::raw('MAX(amount) as amount'),
            DB::raw('MAX(description) as description'),
            DB::raw('MAX(start_date) as start_date'),
            DB::raw('MAX(repeat_interval) as repeat_interval')
        )
            ->groupBy('ref')
            ->where('project_id', $id)
            ->get();
        $projects = Project::all();
        $employers = Employer::all();
        $supplier = Supplier::all();
        return view('project.show', ['project' => $project, 'expenses' => $expenses,'projects' => $projects,'employers' => $employers,'suppliers' => $supplier]);
    }

    public function estimateInvoice($id)
    {
        $project = Project::find($id);

        // Get all orders related to the project
        $orders = $project->orders;

        // Get all expenses related to the project
        $expenses = $project->expenses;

        // Calculate total hours lost by employees
        $tasks = $project->tasks;
        $totalHoursLost = 0;
        foreach ($tasks as $task) {
            $totalHoursLost += $task->duration;
        }

        // Calculate total cost
        $totalOrderCost = $orders->sum('total_price');
        $totalExpenseCost = $expenses->sum('total_amount');
        $totalCost = $totalOrderCost + $totalExpenseCost;

        return view('project.invoice', [
            'project' => $project,
            'orders' => $orders,
            'expenses' => $expenses,
            'totalHoursLost' => $totalHoursLost,
            'totalCost' => $totalCost
        ]);
    }
}