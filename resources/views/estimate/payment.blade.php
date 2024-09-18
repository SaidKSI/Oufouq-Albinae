@extends('layouts.app')
@section('title', 'Invoice Payments')
@section('content')
<x-Breadcrumb title="Invoice Payments" />
<div class="row">
  <div class="card">
    <div class="card-body p-2">
      <div class="card-body p-2">
        <div class="col-md-2 ">
          <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addinvoiceModal">Add</button>
          <!-- Add invoice Modal -->
          <div class="modal fade" id="addinvoiceModal" tabindex="-1" aria-labelledby="addinvoiceModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="addinvoiceModalLabel">Add Project Invoice</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addinvoiceForm" action="{{route('estimatePayment.store')}}" method="POST">
                  @csrf
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="project" class="form-label">Project</label>
                      <select class="form-select" id="project_id" name="project_id">
                        <option disabled selected>Select a Project</option>
                        @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="payment_method" class="form-label">Payment Method</label>
                      <select class="form-select" id="payment_method" name="payment_method" required>
                        <option value="cash">Cash</option>
                        <option value="check">Check</option>
                        <option value="bank_transfer">Bank Transfer</option>
                      </select>
                    </div>
                    <div class="mb-3" id="transaction_id_container" style="display: none;">
                      <label for="transaction_id" class="form-label">Transaction ID</label>
                      <input type="text" class="form-control" id="transaction_id" name="transaction_id">
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <table id="basic-datatable" class="table table-striped dt-responsive">
            <thead>
              <tr>
                <th scope="col">Client</th>
                <th scope="col">Project</th>
                <th scope="col">Reference</th>
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
              $totalWithoutTax = $invoice->total_price / (1 + $invoice->tax / 100);
              $taxAmount = $invoice->total_price - $totalWithoutTax;
              @endphp
              <tr>
                <td>{{ $invoice->project->client->name }}</td>
                <td>{{ $invoice->project->name }}</td>
                <td>{{ $invoice->reference }}</td>
                <td>{{ $invoice->quantity }}</td>
                <td>{{ number_format($invoice->total_price,2) }}</td>
                <td><span data-bs-toggle="tooltip" data-bs-html="true"
                    data-bs-title="<b>{{number_format($taxAmount,2)}}</b>">{{ $invoice->tax }} %</span></td>
                <td>

                  {{ number_format($invoice->total_price + $taxAmount, 2) }}
                </td>
                <td>
                  <a href="#" onclick="printInvoice({{ $invoice->id }})"><i class="ri-file-list-fill"></i></a>


                 {{-- <a href="" class="text-primary" data-bs-toggle="modal"
                    data-bs-target="#invoiceModal{{$invoice->id}}"><i class="ri-edit-fill"></i></a>

                  <!-- Add/Edit Invoice Modal -->
                   <div class="modal fade" id="invoiceModal{{$invoice->id}}" tabindex="-1"
                    aria-labelledby="invoiceModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="invoiceModalLabel">Add Project Invoice</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="invoiceForm" action="{{ route('invoice.update',['id'=>$invoice->id]) }}"
                          method="POST">
                          @method('PUT')
                          @csrf
                          <div class="modal-body">
                            <div class="mb-3">
                              <label for="client" class="form-label">Client</label>
                              <select class="form-select" id="client_id_edit" name="client_id">
                                <option disabled selected>Select a Client</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                              </select>
                            </div>

                            <div class="mb-3">
                              <label for="project" class="form-label">Project</label>
                              <select class="form-select" id="project_id_edit" name="project_id" disabled>
                                <option disabled selected>Select a Project</option>
                              </select>
                            </div>

                            <div class="mb-3">
                              <label for="reference" class="form-label">Reference</label>
                              <input type="text" class="form-control" id="reference" name="reference"
                                value="{{$invoice->reference}}">
                            </div>
                            <div class="mb-3">
                              <label for="quantity" class="form-label">Quantity</label>
                              <input type="number" class="form-control" id="quantity" name="quantity"
                                value="{{$invoice->quantity}}">
                            </div>
                            <div class="mb-3">
                              <label for="total_price" class="form-label">Total Price</label>
                              <input type="number" class="form-control" id="total_price" name="total_price"
                                value="{{$invoice->total_price}}">
                            </div>
                            <div class="mb-3">
                              <label for="tax" class="form-label">Tax <small>(in %)</small></label>
                              <input type="text" class="form-control" id="tax" name="tax" value="{{$invoice->tax}}">
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div> 
                  <a href="#" class="text-danger" onclick="confirmDelete({{$invoice->id}})"><i
                      class="ri-delete-bin-2-fill"></i></a>

                  <form id="delete-form-{{$invoice->id}}" action="{{ route('invoice.destroy', $invoice->id) }}"
                    method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                  </form>--}}

                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- Hidden iframe for printing -->
<iframe id="invoiceFrame" style="display:none;"></iframe>
</div>
@endsection

@push('scripts')
<script>
  function printInvoice(estimateId) {
        var iframe = document.getElementById('invoiceFrame');
        iframe.style.display = 'block';
        iframe.src = '/dashboard/projects/payment/' + estimateId + '/invoice';
        iframe.onload = function() {
            iframe.contentWindow.print();
            iframe.style.display = 'none';
        };
    }
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
        const paymentMethodSelect = document.getElementById('payment_method');
        const transactionIdContainer = document.getElementById('transaction_id_container');

        paymentMethodSelect.addEventListener('change', function () {
            if (this.value === 'check' || this.value === 'bank_transfer') {
                transactionIdContainer.style.display = 'block';
            } else {
                transactionIdContainer.style.display = 'none';
            }
        });
    });
</script>
@endpush