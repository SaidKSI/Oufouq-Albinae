@extends('layouts.app')
@section('title', 'Invoice Payments')
@section('content')
<x-Breadcrumb title="Invoice Payments" />
<div class="row">
  <div class="card">
    <div class="card-body p-2">
      <div class="card-body p-2">
        <div class="col-md-2 ">
          <a href="{{ route('delivery.facture.create') }}" class="btn btn-outline-primary">Create Bon Livraison Facture</a>
        </div>
        <div class="col-md-12">
          <table id="basic-datatable" class="table table-striped dt-responsive">
            <thead>
              <tr>
                <th scope="col">Reference</th>
                <th scope="col">Client</th>
                <th scope="col">Project</th>
                <th scope="col">Quantity</th>
                <th scope="col">Total Price <small>(without tax)</small></th>
                <th scope="col">Tax</th>
                <th scope="col">Total Amount <small>(with tax)</small> </th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($invoices as $invoice)
              @php
              $taxAmount = number_format(($invoice->total_without_tax * 1.2) - $invoice->total_without_tax, 2);
              @endphp
              <tr>
                <td>{{ $invoice->number }}</td>
                <td>{{ $invoice->delivery->project->client->name }}</td>
                <td>{{ $invoice->delivery->project->name }}</td>
                <td>{{ $invoice->delivery->items->sum('qte') }}</td>
                <td>{{ number_format((float)$invoice->total_without_tax, 2) }}</td>
                <td>{{ $taxAmount }}</td>
                <td>{{ number_format((float)$invoice->total_with_tax, 2) }}</td>
                <td>
                  <a href="{{ route('facture.print', $invoice->id) }}" target="_blank" title="Print"><i
                      class="ri-file-fill fs-4"></i></a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
@endpush