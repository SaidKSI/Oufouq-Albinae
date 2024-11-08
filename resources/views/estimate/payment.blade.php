@extends('layouts.app')
@section('title', 'Invoice Payments')
@section('content')
<x-Breadcrumb title="Invoice Payments" />
<div class="row">
  <div class="card">
    <div class="card-body p-2">
      <div class="card-body p-2">
        <div class="col-md-2 ">
          <a href="{{ route('project-estimate.invoice') }}" class="btn btn-outline-primary">Add</a>
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
              $taxAmount = $invoice->total_without_tax * 1.2 - $invoice->total_without_tax;
              @endphp
              <tr>
                <td>{{ $invoice->number }}</td>
                <td>{{ $invoice->estimate->project->client->name }}</td>
                <td>{{ $invoice->estimate->project->name }}</td>
                <td>{{ $invoice->estimate->sum('quantity') }}</td>
                <td>{{ number_format($invoice->total_without_tax,2) }}</td>
                <td>{{ number_format($invoice->tax,2) }}</td>
                <td>

                  {{ number_format($invoice->total_with_tax, 2) }}
                </td>
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