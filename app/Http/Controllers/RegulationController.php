<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Delivery;
use App\Models\Supplier;
use Illuminate\Http\Request;

class RegulationController extends Controller
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
            $entity = $supplierId ? Supplier::find($supplierId) : null;

            if ($supplierId) {
                $query->where('supplier_id', $supplierId);
            }
        } else {
            $clients = Client::all();
            $filterEntity = $clients;
            $selectedEntity = $clientId;
            $entity = $clientId ? Client::find($clientId) : null;

            if ($clientId) {
                $query->where('client_id', $clientId);
            }
        }

        $deliveries = $query->get();
        
        // Calculate totals
        $totalAmount = $deliveries->sum('total_with_tax');
        $totalPaid = $deliveries->sum(function($delivery) {
            return $delivery->total_paid;
        });
        $remainingAmount = $totalAmount - $totalPaid;

        return view('regulation.index', [
            'deliveries' => $deliveries,
            'filterEntity' => $filterEntity,
            'selectedEntity' => $selectedEntity,
            'type' => $type,
            'entity' => $entity,
            'totalAmount' => $totalAmount,
            'totalPaid' => $totalPaid,
            'remainingAmount' => $remainingAmount
        ]);
    }

    
}