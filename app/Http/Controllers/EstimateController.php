<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Estimate;
use Illuminate\Http\Request;
use NumberToWords\NumberToWords;
class EstimateController extends Controller
{
    public function numberToFrenchWords($number)
    {
        $number = (int) round(floatval($number)); // Convert to float, round the number, and then cast to int
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('fr'); // 'fr' for French

        return $numberTransformer->toWords($number);
    }
    public function show($id)
    {
        $estimate = Estimate::findOrFail($id);
        $company = CompanySetting::first();
        $totalWithoutTax = $estimate->total_price / (1 + $estimate->tax / 100);
        $taxAmount = $estimate->total_price - $totalWithoutTax;
        $total = ($estimate->total_price * $estimate->quantity) + $taxAmount;
        $total_in_alphabetic = $this->numberToFrenchWords($total);
        if ($estimate->type === 'estimate') {
            return view('project-invoices.invoice', compact('estimate', 'company', 'total', 'taxAmount', 'totalWithoutTax', 'total_in_alphabetic'));
        } elseif ($estimate->type === 'invoice') {
            return view('project-invoices.invoice', compact('estimate', 'company', 'total', 'taxAmount', 'totalWithoutTax', 'total_in_alphabetic'));
        }
    }
}