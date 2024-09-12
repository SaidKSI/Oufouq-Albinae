<?php

namespace App\Http\Controllers;

use App\Models\CapitalTransaction;
use App\Models\CompanySetting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function index()
    {
        return view('dashboard.index');
    }

    function maintenance()
    {
        return view('maintenance');
    }


    function settings()
    {
        $setting = CompanySetting::first();
        $transactions = CapitalTransaction::all();
        return view('setting.index ', ['setting' => $setting, 'transactions' => $transactions]);
    }

    function storeTransaction(Request $request)
    {
        $request->validate([
            'type' => 'required|in:deposit,withdrawal',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
        ]);

        CapitalTransaction::create([
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);
        // reduce the amount from the company capital
        $settings = CompanySetting::first();
        if ($request->type === 'withdrawal') {
            $settings->capital -= $request->amount;
        } else {
            $settings->capital += $request->amount;
        }
        $settings->save();
        
        return redirect()->back()->with('success', 'Transaction recorded successfully.');
    }

}