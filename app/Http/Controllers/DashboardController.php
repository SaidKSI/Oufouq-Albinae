<?php

namespace App\Http\Controllers;

use App\Models\CapitalTransaction;
use App\Models\CompanySetting;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function index()
    {
        $tasks = Task::all();
        return view('dashboard.index',['tasks' => $tasks]);
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
    public function getCapital()
    {
        $currentCapital = CompanySetting::first()->capital;

        // Calculate the capital from a week ago
        $oneWeekAgo = Carbon::now()->subWeek();
        $transactions = CapitalTransaction::where('created_at', '>=', $oneWeekAgo)->get();

        $totalDeposits = $transactions->where('type', 'deposit')->sum('amount');
        $totalWithdrawals = $transactions->where('type', 'withdrawal')->sum('amount');
        $netChange = $totalDeposits - $totalWithdrawals;

        return response()->json([
            'capital' => number_format($currentCapital, 2),
            'netChange' => number_format($netChange, 2)
        ]);
    }
    public function getCapitalHistory()
    {
        $transactions = CapitalTransaction::orderBy('created_at')->get(['created_at', 'amount', 'type']);

        $capitalHistory = [];
        $currentCapital = 0;

        foreach ($transactions as $transaction) {
            if ($transaction->type == 'deposit') {
                $currentCapital += $transaction->amount;
            } else {
                $currentCapital -= $transaction->amount;
            }
            $capitalHistory[] = [
                'date' => $transaction->created_at->format('Y-m-d'),
                'capital' => $currentCapital
            ];
        }

        return response()->json($capitalHistory);
    }
}