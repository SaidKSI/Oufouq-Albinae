<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Estimate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    function index()
    {
        $clients = Client::all();
        $clientCount = $clients->count();
        return view('client.index', ['clients' => $clients, 'clientCount' => $clientCount]);
    }
    function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'city' => 'required|string',
            'ice' => 'required|string',
            'type' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, ['company', 'person'])) {
                        $fail($attribute . ' is invalid.');
                    }
                }
            ]
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors('error', $validator->errors());
        }

        $client = new Client();
        $client->name = $request->name;
        $client->phone = $request->phone;
        $client->email = $request->email;
        $client->address = $request->address;
        $client->city = $request->city;
        $client->ice = $request->ice;
        $client->type = $request->type;
        $client->save();

        return redirect()->route('client.index')->with('success', 'Client added successfully.');

    }


    
    function update(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'city' => 'required|string',
            'ice' => 'required|string',
            'type' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, ['company', 'person'])) {
                        $fail($attribute . ' is invalid.');
                    }
                }
            ]
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors('error', $validator->errors());
        }

        $client = Client::findOrFail($id);
        $client->name = $request->name;
        $client->phone = $request->phone;
        $client->email = $request->email;
        $client->address = $request->address;
        $client->city = $request->city;
        $client->ice = $request->ice;
        $client->type = $request->type;
        $client->save();

        return redirect()->back()->with('success', 'Client updated successfully.');
    }

    function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return redirect()->back()->with('success', 'Client deleted successfully.');
    }

    function getClientProjects($id)
    {
        $client = Client::findOrFail($id);
        $projects = $client->projects;
        
        return response()->json($projects);
    }
}