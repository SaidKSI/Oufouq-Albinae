<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    function index()
    {
        $projects = Project::all();
        $employers = Employer::all();
        $tasks = Task::all();
        return view('task.index', compact('projects', 'employers', 'tasks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
            'priority' => 'required|integer',
            'employer_ids' => 'required|array',
            'employer_ids.*' => 'exists:employers,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors('error', $validator->errors());
        }

        $task = new Task();
        $task->project_id = $request->project_id;
        $task->name = $request->name;
        $task->description = $request->description;
        $task->duration = $request->duration;
        $task->priority = $request->priority;
        $task->date = $request->date;
        $task->save();

        // Sync the employer_ids with the task
        $task->employees()->sync($request->employer_ids);

        return redirect()->back()->with('success', 'Task created successfully.');
    }

    function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
            'priority' => 'required|integer',
            'employer_ids' => 'required|array',
            'progress' => 'required|integer',
            'employer_ids.*' => 'exists:employers,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors('error', $validator->errors());
        }
       
        $task = Task::find($id);	
        $task->project_id = $request->project_id;
        $task->name = $request->name;
        $task->description = $request->description;
        $task->duration = $request->duration;
        $task->priority = $request->priority;
        $task->date = $request->date;
        $task->progress = $request->progress;
        if($request->progress == 100){
            $task->status = 'completed';
        }
        $task->save();

        // Sync the employer_ids with the task
        $task->employees()->sync($request->employer_ids);

        return redirect()->back()->with('success', 'Task updated successfully.');
    }
}