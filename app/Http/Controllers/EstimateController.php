<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CompanySetting;
use App\Models\Estimate;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use NumberToWords\NumberToWords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
    protected function handleDocumentUpload($estimate, $request)
    {
        if ($request->hasFile('doc')) {
            $files = $request->file('doc');
            // Ensure $files is always an array
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                if ($file->isValid()) {
                    try {
                        // Store file in public storage
                        $path = $file->store('estimate_documents', 'public');

                        // Create document record
                        $estimate->documents()->create([
                            'path' => $path,
                            'name' => $file->getClientOriginalName(),
                            'documentable_type' => Estimate::class,
                            'documentable_id' => $estimate->id
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Document upload failed: ' . $e->getMessage());
                        throw new \Exception('Failed to upload document: ' . $e->getMessage());
                    }
                }
            }
        }
    }
    function storeInvoice(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'number' => 'required|unique:estimates,number',
            'date' => 'required|date',
            'ref' => 'required|array',
            'ref.*' => 'required|string',
            'name' => 'required|array',
            'name.*' => 'required|string',
            'qte' => 'required|array',
            'qte.*' => 'required|numeric',
            'prix_unite' => 'required|array',
            'prix_unite.*' => 'required|numeric',
            'category' => 'required|array',
            'category.*' => 'required|string',
            'total_price_unite' => 'required|array',
            'total_without_tax' => 'required|numeric',
            'tax' => 'required|numeric',
            'total_with_tax' => 'required|numeric',
            'doc' => 'nullable|file',
            'note' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }

        try {
            DB::beginTransaction();

            // Create the main estimate
            $estimate = Estimate::create([
                'project_id' => $request->project_id,
                'number' => $request->number,
                'type' => 'estimate',
                'total_without_tax' => $request->total_without_tax,
                'total_with_tax' => $request->total_with_tax,
                'due_date' => $request->date,
                'tax' => $request->total_with_tax - $request->total_without_tax,
                'note' => $request->note,
            ]);

            // Create estimate items
            foreach ($request->ref as $index => $ref) {
                $estimate->items()->create([
                    'ref' => $ref,
                    'name' => $request['name'][$index],
                    'qte' => $request['qte'][$index],
                    'prix_unite' => $request['prix_unite'][$index],
                    'category' => $request['category'][$index],
                    'total_price_unite' => $request['total_price_unite'][$index],
                ]);
            }

            // Handle file uploads
            $this->handleDocumentUpload($estimate, $request);

            DB::commit();
            return redirect()->route('estimates')->with('success', 'Estimate created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error('An error occurred while creating the estimate');
            return back()->withInput();
        }
    }
    public function getEstimateDetails(Estimate $estimate)
    {
        return response()->json([
            'total_price' => $estimate->total_price,
            'tax' => $estimate->tax,
            'items' => $estimate->items,
            'note' => $estimate->note,
            'project' => $estimate->project,
            'documents' => $estimate->documents->map(function ($doc) {
                return [
                    'name' => $doc->name,
                    'url' => Storage::url($doc->path), // Get public URL
                    'path' => $doc->path
                ];
            })
        ]);
    }
}