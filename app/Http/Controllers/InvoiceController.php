<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show(Estimate $invoice)
    {
        return view('invoice.show', compact('invoice'));
    }

    public function print(Estimate $invoice)
    {
        return view('invoice.print', compact('invoice'));
    }
}