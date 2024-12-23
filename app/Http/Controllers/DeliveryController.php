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


    public function deliveryInvoice($type, Request $request)
    {
        $tax_type = $request->query('tax_type', 'normal');
        $selectedClientId = $request->query('client_id');
        $selectedProjectId = $request->query('project_id');

        $clients = Client::all();
        $suppliers = Supplier::all();
        $company = CompanySetting::first();

        return view('delivery.invoice', compact(
            'type',
            'tax_type',
            'clients',
            'suppliers',
            'company',
            'selectedClientId',
            'selectedProjectId'
        ));
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
            'facture' => 'required|in:true,false',
            'tax_type' => 'required|in:normal,included,no_tax',
        ], [
            'number.string' => 'The number must be a string',
            'date.required' => 'The date is required',
            'date.date' => 'Invalid date format',
            'client_id.required' => 'Please select a client',
            'client_id.exists' => 'Selected client does not exist',
            'project_id.required' => 'Please select a project',
            'project_id.exists' => 'Selected project does not exist',
            'supplier_id.required_if' => 'Please select a supplier',
            'supplier_id.exists' => 'Selected supplier does not exist',
            'ref.required' => 'Reference is required',
            'ref.*.required' => 'All references are required',
            'ref.*.string' => 'References must be strings',
            'name.required' => 'Name is required',
            'name.*.required' => 'All names are required',
            'name.*.string' => 'Names must be strings',
            'qte.required' => 'Quantity is required',
            'qte.*.required' => 'All quantities are required',
            'qte.*.numeric' => 'Quantities must be numbers',
            'prix_unite.required' => 'Unit price is required',
            'prix_unite.*.required' => 'All unit prices are required',
            'prix_unite.*.numeric' => 'Unit prices must be numbers',
            'category.required' => 'Category is required',
            'category.*.required' => 'All categories are required',
            'category.*.string' => 'Categories must be strings',
            'total_price_unite.required' => 'Total unit price is required',
            'total_without_tax.required' => 'Total without tax is required',
            'total_without_tax.numeric' => 'Total without tax must be a number',
            'tax.required' => 'Tax is required',
            'tax.numeric' => 'Tax must be a number',
            'total_with_tax.required' => 'Total with tax is required',
            'total_with_tax.numeric' => 'Total with tax must be a number',
            'payment_method.required' => 'Payment method is required',
            'payment_method.string' => 'Payment method must be a string',
            'type.required' => 'Type is required',
            'type.string' => 'Type must be a string',
            'facture.required' => 'Please specify if this is a facture',
            'facture.in' => 'Invalid facture value',
            'tax_type.required' => 'Tax type is required',
            'tax_type.in' => 'Invalid tax type'
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
            'facture' => $request['facture'] == 'true' ? true : false,
            'tax_type' => $request['tax_type'],
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
            return redirect()->back()->with('success', 'Delivery deleted successfully.');
        }
        return redirect()->back()->with('error', 'Delivery not found.');
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


}