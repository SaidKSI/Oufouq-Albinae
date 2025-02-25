<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Delivery;
use App\Models\Estimate;
use App\Models\Facture;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function facturePrint(Facture $invoice)
    {
        return view('invoice.facture', compact('invoice'));
    }

    public function estimatePrint(Estimate $estimate)
    {
        return view('invoice.estimate', compact('estimate'));
    }

    public function deliveryPrint(Delivery $delivery)
    {
        return view('invoice.delivery', compact('delivery'));
    }

    public function print(Request $request, $type)
    {
        // Reuse the same logic as index
        $supplierId = $request->input('supplier_id');
        $clientId = $request->input('client_id');

        $query = Delivery::where('type', $type);

        if ($type === 'supplier') {
            $entity = $supplierId ? Supplier::find($supplierId) : null;
            if ($supplierId) {
                $query->where('supplier_id', $supplierId);
            }
        } else {
            $entity = $clientId ? Client::find($clientId) : null;
            if ($clientId) {
                $query->where('client_id', $clientId);
            }
        }

        $deliveries = $query->get();

        $totalAmount = $deliveries->sum('total_with_tax');
        $totalPaid = $deliveries->sum(function ($delivery) {
            return $delivery->total_paid;
        });
        $remainingAmount = $totalAmount - $totalPaid;

        return view('invoice.regulation', [
            'deliveries' => $deliveries,
            'type' => $type,
            'entity' => $entity,
            'totalAmount' => $totalAmount,
            'totalPaid' => $totalPaid,
            'remainingAmount' => $remainingAmount,
            'date' => now()
        ]);
    }
}