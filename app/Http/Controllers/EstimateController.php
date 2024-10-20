<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CompanySetting;
use App\Models\Estimate;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

    public function createInvoice(Request $request)
    {
        $clients = Client::all(); 
        $selectedClientId = $request->input('client_id');
        $selectedProjectId = $request->input('project_id');
        return view('estimate.create-invoice', compact('clients', 'selectedClientId', 'selectedProjectId'));
    }

    function storeInvoice(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'number' => 'required|unique:estimates,number',
            'date' => 'required|date',
            'reference' => 'required|string',
            'qte' => 'required|numeric|min:0',
            'total_without_tax' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0|max:100',
            'total_with_tax' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'doc.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }


        $estimate = new Estimate();
        $estimate->project_id = $request->project_id;
        $estimate->number = $request->number;
        $estimate->reference = $request->reference;
        $estimate->type = 'estimate';
        $estimate->quantity = $request->qte;
        $estimate->total_price = $request->total_with_tax;
        $estimate->tax = $request->tax;
        $estimate->note = $request->note;
        $estimate->save();

        // Handle file uploads
        if ($request->has('doc')) {
            $file = $request->file('doc');
            $path = $file->store('invoice_documents', 'public');
            
            $estimate->documents()->create([
                'path' => $path,
                'name' => $file->getClientOriginalName(),
            ]);
        }

        return redirect()->route('estimates')->with('success', 'Invoice created successfully');

    }
}