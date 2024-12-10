<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employer;
use App\Models\Payment;
use App\Models\Profession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployerController extends Controller
{
    function index()
    {
        $professions = Profession::all();
        $employers = Employer::all();
        return view('employer.index', ['professions' => $professions, 'employers' => $employers]);
    }

    function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string',
            'phone' => 'required|string',
            'city' => 'required|string',
            'cine' => 'required|string',
            'address' => 'required|string',
            'cnss' => 'nullable|string',
            'wage_per_hr' => 'required|numeric',
            'profession_id' => 'required|exists:professions,id',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }

        $employer = new Employer();
        $employer->full_name = $request->full_name;
        $employer->phone = $request->phone;
        $employer->city = $request->city;
        $employer->cine = $request->cine;
        $employer->address = $request->address;
        $employer->cnss = $request->cnss ? true : false;
        $employer->wage_per_hr = $request->wage_per_hr;
        $employer->profession_id = $request->profession_id;
        $employer->save();

        // toastr()->success('Employer added successfully');

        return redirect()->back()->with('success', 'Employer added successfully');
    }

    function profession()
    {
        $professions = Profession::all();
        return view('employer.profession', ['professions' => $professions]);
    }

    function storeProfession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }

        $profession = new Profession();
        $profession->name = $request->name;
        $profession->save();

        // toastr()->success('Profession added successfully');

        return redirect()->back()->with('success', 'Profession added successfully');
    }
    function updateProfession(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }

        $profession = Profession::find($id);
        $profession->name = $request->name;
        $profession->save();

        return redirect()->back()->with('success', 'Profession updated successfully');
    }
    function deleteProfession($id)
    {
        $profession = Profession::find($id);
        $profession->delete();
        return redirect()->back()->with('success', 'Profession deleted successfully');
    }

    public function destroy($id)
    {
        $employer = Employer::findOrFail($id);
        $employer->delete();
        return redirect()->back()->with('success', 'Employer deleted successfully');
    }
}