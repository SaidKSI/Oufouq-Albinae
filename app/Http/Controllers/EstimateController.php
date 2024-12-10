<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CompanySetting;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\Estimate;
use App\Models\Facture;
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
        $taxType = $request->input('tax_type', 'normal'); // Default to normal if not specified

        return view('estimate.create-invoice', compact('clients', 'selectedClientId', 'selectedProjectId', 'taxType'));
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
            'note' => 'nullable|string',
            'tax_type' => 'required|in:normal,included,no_tax'
        ], [
            'project_id.required' => 'The project ID is required.',
            'project_id.exists' => 'The selected project does not exist.',
            'number.required' => 'The invoice number is required.',
            'number.unique' => 'This invoice number has already been used.',
            'date.required' => 'The date is required.',
            'date.date' => 'Please enter a valid date.',
            'ref.required' => 'The reference field is required.',
            'ref.array' => 'The reference must be an array.',
            'ref.*.required' => 'Each reference field is required.',
            'ref.*.string' => 'Each reference must be text.',
            'name.required' => 'The name field is required.',
            'name.array' => 'The name must be an array.',
            'name.*.required' => 'Each name field is required.',
            'name.*.string' => 'Each name must be text.',
            'qte.required' => 'The quantity field is required.',
            'qte.array' => 'The quantity must be an array.',
            'qte.*.required' => 'Each quantity field is required.',
            'qte.*.numeric' => 'Each quantity must be a number.',
            'prix_unite.required' => 'The unit price field is required.',
            'prix_unite.array' => 'The unit price must be an array.',
            'prix_unite.*.required' => 'Each unit price field is required.',
            'prix_unite.*.numeric' => 'Each unit price must be a number.',
            'category.required' => 'The category field is required.',
            'category.array' => 'The category must be an array.',
            'category.*.required' => 'Each category field is required.',
            'category.*.string' => 'Each category must be text.',
            'total_price_unite.required' => 'The total unit price field is required.',
            'total_price_unite.array' => 'The total unit price must be an array.',
            'total_without_tax.required' => 'The total without tax is required.',
            'total_without_tax.numeric' => 'The total without tax must be a number.',
            'tax.required' => 'The tax amount is required.',
            'tax.numeric' => 'The tax must be a number.',
            'total_with_tax.required' => 'The total with tax is required.',
            'total_with_tax.numeric' => 'The total with tax must be a number.',
            'doc.file' => 'The document must be a file.',
            'tax_type.required' => 'The tax type is required.',
            'tax_type.in' => 'Invalid tax type selected.'
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
                'tax_type' => $request->tax_type,
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
            toastr()->error('An error occurred while creating the estimate', $e->getMessage());
            return back()->withInput();
        }
    }
    public function getEstimateDetails(Estimate $estimate)
    {
        return response()->json([
            'total_without_tax' => $estimate->total_without_tax,
            'total_with_tax' => $estimate->total_with_tax,
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
    public function storeFacture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'estimate_id' => 'required|exists:estimates,id',
            'number' => 'required|unique:factures,number',
            'date' => 'required|date',
            'payment_method' => 'required|in:bank_transfer,cheque,credit,cash,traita,other',
            'transaction_id' => 'nullable|string',
            'total_without_tax' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'total_with_tax' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'doc.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ], [
            'estimate_id.required' => 'The estimate ID is required.',
            'estimate_id.exists' => 'The selected estimate does not exist.',
            'number.required' => 'The invoice number is required.',
            'number.unique' => 'This invoice number has already been used.',
            'date.required' => 'The date is required.',
            'date.date' => 'Please enter a valid date.',
            'payment_method.required' => 'The payment method is required.',
            'payment_method.in' => 'Invalid payment method selected.',
            'total_without_tax.required' => 'The total without tax is required.',
            'total_without_tax.numeric' => 'The total without tax must be a number.',
            'total_without_tax.min' => 'The total without tax cannot be negative.',
            'tax.required' => 'The tax amount is required.',
            'tax.numeric' => 'The tax must be a number.',
            'tax.min' => 'The tax cannot be negative.',
            'total_with_tax.required' => 'The total with tax is required.',
            'total_with_tax.numeric' => 'The total with tax must be a number.',
            'total_with_tax.min' => 'The total with tax cannot be negative.',
            'doc.*.mimes' => 'Documents must be PDF, DOC, DOCX, JPG, JPEG or PNG files.',
            'doc.*.max' => 'Documents must not exceed 2MB in size.'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }

        try {
            DB::beginTransaction();

            // Format numbers
            $totalWithoutTax = number_format((float) $request->total_without_tax, 2, '.', '');
            $tax = number_format((float) $request->tax, 2, '.', '');
            $totalWithTax = number_format((float) $request->total_with_tax, 2, '.', '');

            // Create the facture
            $facture = Facture::create([
                'estimate_id' => $request->estimate_id,
                'delivery_id' => null,  // Since this is for estimate
                'number' => $request->number,
                'date' => $request->date,
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'total_without_tax' => $totalWithoutTax,
                'tax' => $tax,
                'total_with_tax' => $totalWithTax,
                'note' => $request->note
            ]);

            // Handle document uploads if any
            if ($request->hasFile('doc')) {
                foreach ($request->file('doc') as $file) {
                    $path = $file->store('facture-documents', 'public');
                    $facture->documents()->create([
                        'name' => $file->getClientOriginalName(),
                        'path' => $path
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('factures.index')
                ->with('success', 'Facture created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Facture Creation Error: ' . $e->getMessage());
            toastr()->error('An error occurred while creating the facture: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function convertToFacture(Request $request, Estimate $estimate)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string',
            'reference' => 'nullable|string',
        ], [
            'payment_method.required' => 'The payment method is required.',
            'transaction_id.required' => 'The transaction ID is required.',
            'reference.required' => 'The reference is required.',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }
        // check if the estimate is already converted to facture
        if ($estimate->hasFacture()) {
            toastr()->error('This estimate has already been converted to a facture.');
            return back();
        }
        // Create new facture from estimate
        $facture = Facture::create([
            'estimate_id' => $estimate->id,
            'number' => $request->reference,
            'date' => now(),
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'total_without_tax' => $estimate->total_without_tax,
            'tax' => $estimate->tax,
            'total_with_tax' => $estimate->total_with_tax,
            'note' => $estimate->note,
            'tax_type' => $estimate->tax_type,
        ]);

        return redirect()->route('facture.print', $facture->id)
            ->with('success', 'Estimate converted to invoice successfully');
    }

    public function convertToDelivery(Request $request, Estimate $estimate)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
        ], [
            'payment_method.required' => 'The payment method is required.',
            'transaction_id.required' => 'The transaction ID is required.',
            'reference.required' => 'The reference is required.',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }
        // check if the estimate is already converted to delivery
        if ($estimate->hasDelivery()) {
            toastr()->error('This estimate has already been converted to a delivery.');
            return back();
        }
        try {
            DB::beginTransaction();

            // Create new delivery from estimate
            $delivery = Delivery::create([
                'number' => $request->reference,
                'date' => now(),
                'client_id' => $estimate->project->client_id,
                'project_id' => $estimate->project_id,
                'total_without_tax' => $estimate->total_without_tax,
                'tax' => $estimate->tax,
                'total_with_tax' => $estimate->total_with_tax,
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'note' => $estimate->note,
                'type' => 'client',
                'tax_type' => $estimate->tax_type,
            ]);

            // Copy estimate items to delivery items
            foreach ($estimate->items as $item) {
                $delivery->items()->create([
                    'delivery_id' => $delivery->id,
                    'ref' => $item->ref,
                    'name' => $item->name,
                    'qte' => $item->qte,
                    'prix_unite' => $item->prix_unite,
                    'category' => $item->category,
                    'total_price_unite' => $item->total_price_unite,
                ]);
            }

            DB::commit();

            return redirect()->route('delivery.print', $delivery->id)
                ->with('success', 'Estimate converted to delivery note successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error converting estimate to delivery: ' . $e->getMessage());
        }
    }
}