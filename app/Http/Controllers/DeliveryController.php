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
    function index(Request $request)
    {
        $supplierId = $request->input('supplier_id');
        $suppliers = Supplier::all();

        if ($supplierId) {
            $deliveries = Delivery::where('supplier_id', $supplierId)->get();
        } else {
            $deliveries = Delivery::all();
        }

        return view('delivery.index', [
            'deliveries' => $deliveries,
            'suppliers' => $suppliers,
            'selectedSupplier' => $supplierId
        ]);
    }


    function deliveryInvoice()
    {
        $clients = Client::all();
        $suppliers = Supplier::all();
        return view('delivery.invoice', ['clients' => $clients, 'suppliers' => $suppliers]);
    }

    function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'number' => 'nullable|string',
            'date' => 'required|date',
            'client_id' => 'required|integer|exists:clients,id',
            'project_id' => 'required|integer|exists:projects,id',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'ref' => 'required|array',
            'name' => 'required|array',
            'qte' => 'required|array',
            'prix_unite' => 'required|array',
            'category' => 'required|array',
            'total_price_unite' => 'required|array',
            'total_without_tax' => 'required|numeric',
            'tax' => 'required|numeric',
            'total_with_tax' => 'required|numeric',
            'doc' => 'nullable|file',
            'note' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }
        // Handle file upload
        $docPath = null;
        if ($request->hasFile('doc')) {
            $docPath = $request->file('doc')->store('delivery-docs', 'public');
        }

        // Create a new delivery record
        $delivery = Delivery::create([
            'number' => $request['number'],
            'date' => $request['date'],
            'client_id' => $request['client_id'],
            'project_id' => $request['project_id'],
            'supplier_id' => $request['supplier_id'],
            'total_without_tax' => $request['total_without_tax'],
            'tax' => $request['tax'],
            'total_with_tax' => $request['total_with_tax'],
            'doc' => $docPath,
            'note' => $request['note'],
        ]);

        // Store delivery items
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

        return redirect()->route('delivery')->with('success', 'Delivery created successfully.');
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
        
        return view('delivery.show', ['delivery' => $delivery, 'totalInAlphabet' => $totalInAlphabet,'company' => $company]);
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

        return view('delivery.print', ['delivery' => $delivery, 'totalInAlphabet' => $totalInAlphabet,'company' => $company]);
    }
}