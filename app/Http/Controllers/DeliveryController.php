<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CompanySetting;
use App\Models\Delivery;
use App\Models\Supplier;
use Illuminate\Http\Request;
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
}