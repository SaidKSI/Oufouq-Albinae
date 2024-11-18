<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CompanySetting;
use App\Models\Delivery;
use App\Models\Facture;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use NumberToWords\NumberToWords;

class DeliveryController extends Controller
{
    public function index(Request $request, $type)
    {
        $supplierId = $request->input('supplier_id');
        $clientId = $request->input('client_id');

        $query = Delivery::where('type', $type);

        if ($type === 'supplier') {
            $suppliers = Supplier::all();
            $filterEntity = $suppliers;
            $selectedEntity = $supplierId;

            if ($supplierId) {
                $query->where('supplier_id', $supplierId);
            }
        } else {
            $clients = Client::all();
            $filterEntity = $clients;
            $selectedEntity = $clientId;

            if ($clientId) {
                $query->where('client_id', $clientId);
            }
        }

        $deliveries = $query->get();

        return view('delivery.index', [
            'deliveries' => $deliveries,
            'filterEntity' => $filterEntity,
            'selectedEntity' => $selectedEntity,
            'type' => $type
        ]);
    }


    public function deliveryInvoice(Request $request, $type)
    {
        $clients = Client::all();
        $suppliers = Supplier::all();
        $selectedClientId = $request->input('client_id');
        $selectedProjectId = $request->input('project_id');
        return view('delivery.invoice', [
            'type' => $type,
            'clients' => $clients,
            'suppliers' => $suppliers,
            'selectedClientId' => $selectedClientId,
            'selectedProjectId' => $selectedProjectId
        ]);
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'nullable|string',
            'date' => 'required|date',
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'required|exists:projects,id',
            'supplier_id' => 'required_if:type,supplier|exists:suppliers,id',
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
            'payment_method' => 'required|string',
            'type' => 'required|string',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }
        $docPath = null;
        if ($request->hasFile('doc')) {
            $docPath = $request->file('doc')->store('delivery-docs', 'public');
        }

        $delivery = Delivery::create([
            'number' => $request['number'],
            'date' => $request['date'],
            'client_id' => $request['client_id'],
            'project_id' => $request['project_id'],
            'supplier_id' => $request['supplier_id'],
            'total_without_tax' => $request['total_without_tax'],
            'tax' => $request['total_with_tax'] - $request['total_without_tax'],
            'total_with_tax' => $request['total_with_tax'],
            'doc' => $docPath,
            'note' => $request['note'],
            'payment_method' => $request['payment_method'],
            'type' => $request['type'],
        ]);

        foreach ($request['ref'] as $index => $ref) {
            $delivery->items()->create([
                'ref' => $ref,
                'name' => $request['name'][$index],
                'qte' => $request['qte'][$index],
                'prix_unite' => $request['prix_unite'][$index],
                'category' => $request['category'][$index],
                'total_price_unite' => $request['total_price_unite'][$index],
            ]);
        }

        return redirect()->route('delivery.index', ['type' => $request['type']])->with('success', 'Invoice created successfully.');
    }

    function show($id)
    {
        $delivery = Delivery::findOrFail($id);
        $number = (int) round(floatval($delivery->total_with_tax));
        // dd($number);
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('fr'); // 'fr' for French

        $totalInAlphabet = $numberTransformer->toWords($number);
        $company = CompanySetting::first();

        return view('delivery.show', ['delivery' => $delivery, 'totalInAlphabet' => $totalInAlphabet, 'company' => $company]);
    }

    public function numberToFrenchWords($number)
    {
        $number = (int) round(floatval($number)); // Convert to float, round the number, and then cast to int
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('fr'); // 'fr' for French

        return response()->json($numberTransformer->toWords($number));
    }
    function destroy($id)
    {
        $delivery = Delivery::find($id);
        if ($delivery) {
            $delivery->delete();
            return redirect()->route('delivery')->with('success', 'Delivery deleted successfully.');
        }
        return redirect()->route('delivery')->with('error', 'Delivery not found.');
    }

    function print($id)
    {
        $delivery = Delivery::findOrFail($id);
        $number = (int) round(floatval($delivery->total_with_tax));
        // dd($number);
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('fr'); // 'fr' for French

        $totalInAlphabet = $numberTransformer->toWords($number);
        $company = CompanySetting::first();

        return view('delivery.print', ['delivery' => $delivery, 'totalInAlphabet' => $totalInAlphabet, 'company' => $company]);
    }

    public function addBill(Request $request, $id)
    {
        $delivery = Delivery::findOrFail($id);

        $validatedData = $request->validate([
            'bill_number' => 'required|string',
            'bill_date' => 'required|date',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'note' => 'nullable|string',
        ]);

        if ($validatedData['amount'] > $delivery->remaining_amount) {
            return redirect()->back()->with('error', 'The bill amount exceeds the remaining amount for this delivery.');
        }

        $delivery->bills()->create($validatedData);

        return redirect()->back()->with('success', 'Bill added successfully');
    }

    public function getDeliveryDetails($id)
    {
        try {
            $delivery = Delivery::with(['items', 'project', 'client'])->findOrFail($id);
            
            return response()->json([
                'delivery_id' => $delivery->id,
                'project_id' => $delivery->project_id,
                'total_without_tax' => $delivery->total_without_tax,
                'total_with_tax' => $delivery->total_with_tax,
                'tax' => $delivery->tax,
                'items' => $delivery->items->map(function ($item) {
                    return [
                        'ref' => $item->ref,
                        'name' => $item->name,
                        'qte' => $item->qte,
                        'prix_unite' => $item->prix_unite,
                        'category' => $item->category,
                        'total_price_unite' => $item->total_price_unite
                    ];
                }),
                'note' => $delivery->note,
                'project' => [
                    'id' => $delivery->project->id,
                    'name' => $delivery->project->name,
                    'client' => [
                        'id' => $delivery->client->id,
                        'name' => $delivery->client->name
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Delivery Details Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch delivery details',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    protected function handleDocumentUpload($delivery, $request)
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
                        $delivery->documents()->create([
                            'path' => $path,
                            'name' => $file->getClientOriginalName(),
                            'documentable_type' => Delivery::class,
                            'documentable_id' => $delivery->id
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Document upload failed: ' . $e->getMessage());
                        throw new \Exception('Failed to upload document: ' . $e->getMessage());
                    }
                }
            }
        }
    }
    public function storeFacture(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'delivery_id' => 'required|exists:deliveries,id',
                'number' => 'required|string|unique:factures,number',
                'date' => 'required|date',
                'payment_method' => 'required|in:bank_transfer,cheque,credit,cash,traita',
                'transaction_id' => 'nullable|string',
                'total_without_tax' => 'required|numeric|min:0',
                'tax' => 'required|numeric|min:0',
                'total_with_tax' => 'required|numeric|min:0',
                'note' => 'nullable|string',
                'doc' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
            ], [
                'delivery_id.required' => 'The delivery ID is required.',
                'delivery_id.exists' => 'The selected delivery does not exist.',
                'number.required' => 'The invoice number is required.',
                'number.unique' => 'This invoice number has already been used.',
                'date.required' => 'The date is required.',
                'date.date' => 'Please enter a valid date.',
                'payment_method.required' => 'The payment method is required.',
                'payment_method.in' => 'Please select a valid payment method.',
                'total_without_tax.required' => 'The total without tax is required.',
                'total_without_tax.numeric' => 'The total without tax must be a number.',
                'total_without_tax.min' => 'The total without tax cannot be negative.',
                'tax.required' => 'The tax amount is required.',
                'tax.numeric' => 'The tax must be a number.',
                'tax.min' => 'The tax cannot be negative.',
                'total_with_tax.required' => 'The total with tax is required.',
                'total_with_tax.numeric' => 'The total with tax must be a number.',
                'total_with_tax.min' => 'The total with tax cannot be negative.',
                'doc.file' => 'The document must be a file.',
                'doc.mimes' => 'The document must be a PDF, DOC, DOCX, JPG, JPEG or PNG file.',
                'doc.max' => 'The document must not be larger than 2MB.'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $errorMessage = implode('<br>', $errors);
                toastr()->error($errorMessage);
                return back()->withErrors($validator->errors())->withInput();
            }
    

            // Format numbers
            $totalWithoutTax = number_format((float)$request->total_without_tax, 2, '.', '');
            $tax = number_format((float)$request->tax, 2, '.', '');
            $totalWithTax = number_format((float)$request->total_with_tax, 2, '.', '');

            // Get the delivery
            $delivery = Delivery::findOrFail($request->delivery_id);
            
            // Create the facture
            $facture = Facture::create([
                'estimate_id' => null,  // Explicitly set to null for delivery factures
                'delivery_id' => $delivery->id,
                'number' => $request->number,
                'date' => $request->date,
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'total_without_tax' => $totalWithoutTax,
                'tax' => $tax,
                'total_with_tax' => $totalWithTax,
                'note' => $request->note,
            ]);

            // If there are any documents to attach
            $this->handleDocumentUpload($facture, $request);

            return redirect()->back()
                ->with('success', 'Facture created successfully');

        } catch (\Exception $e) {
            Log::error('Facture Creation Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create facture: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function facture() {
        $invoices = Facture::where('estimate_id', null)->get();
        return view('delivery.facture.index', ['invoices' => $invoices]);
    }
    // ? create facture
    public function createFacture()
    {
        $deliveries = Delivery::all();
        return view('delivery.facture.create', ['deliveries' => $deliveries]);
    }
    
}