<?php

namespace App\Http\Controllers;

use App\Models\Profession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployerController extends Controller
{
    function index()
    {
        $professions = Profession::all();
        return view('employer.index',['professions' => $professions]);
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'full_name' => 'required|string',
           'phone' => 'required|string',
           'city' => 'required|string',
           'cine' => 'required|string',
           'address' => 'required|string',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }
    }
}