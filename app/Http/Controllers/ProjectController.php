<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;
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
            'progress_percentage'=>'required|integer'
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
}