<?php

namespace App\Listeners;

use App\Models\CompanySetting;
use App\Models\CapitalTransaction;

class ReduceCapital
{
    /**
     * Handle the event.
     */
    public function handle($event)
    {
        // Reduce the company capital
        $company = CompanySetting::first();
        $company->capital -= $event->amount;
        $company->save();
       
        // Record the transaction
       $transaction =  CapitalTransaction::create([
            'type' => 'withdrawal',
            'amount' => $event->amount,
            'description' => 'Automatic deduction for ' . class_basename($event),
        ]);

        // dd($company,$transaction);

    }
}