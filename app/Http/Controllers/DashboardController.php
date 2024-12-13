<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use App\Models\CapitalTransaction;
use App\Models\CompanySetting;
use App\Models\Delivery;
use App\Models\Estimate;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $tasks = Task::all();

        // Get estimates not converted to delivery notes
        $notConvertedToDelivery = Estimate::where('type', 'estimate')
            ->doesntHave('delivery')
            ->with(['project.client'])
            ->get();

        // Get estimate notes not converted to factures
        $notConvertedToFacture = Estimate::where('type', 'estimate')
            ->doesntHave('facture')
            ->with(['project.client', 'items'])
            ->get();

        return view('dashboard.index', [
            'tasks' => $tasks,
            'notConvertedToDelivery' => $notConvertedToDelivery,
            'notConvertedToFacture' => $notConvertedToFacture
        ]);
    }

    function maintenance()
    {
        return view('maintenance');
    }


    function settings()
    {
        $backups = Backup::orderBy('created_at', 'desc')->get();
        $setting = CompanySetting::first();
        $transactions = CapitalTransaction::all();
        return view('setting.index ', ['setting' => $setting, 'transactions' => $transactions, 'backups' => $backups]);
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

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone1' => 'nullable|string|max:255',
            'phone2' => 'nullable|string|max:255',
            'fax' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'if' => 'nullable|string|max:255',
            'ice' => 'nullable|string|max:255',
            'rc' => 'nullable|string|max:255',
            'cnss' => 'nullable|string|max:255',
            'patente' => 'nullable|string|max:255',
            'capital' => 'nullable|numeric',
            'website' => 'nullable|url|max:255',
            'footer_text' => 'nullable|string',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'bank_rib' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $setting = CompanySetting::first();

            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($setting->logo) {
                    Storage::disk('public')->delete($setting->logo);
                }
                $logoPath = $request->file('logo')->store('logos', 'public');
                $validated['logo'] = $logoPath;
            }

            $setting->update($validated);

            return redirect()->back()->with('success', 'Company settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }
}