@extends('layouts.app')
@section('title', 'Delivery')
@section('content')
<x-Breadcrumb title="Delivery" />
<div class="row">
  <div class="card">
    <div class="col-md-2 mx-3 my-1">
      <a href="{{route('delivery.invoice')}}" class="btn btn-outline-primary">Create invoice</a>
    </div>
    <form method="GET" action="{{ route('delivery') }}">
      <div class="form-group">
        <label for="supplier_id">Filter by Supplier</label>
        <select name="supplier_id" id="supplier_id" class="form-control w-25 m-2" onchange="this.form.submit()">
          <option value="">All Suppliers</option>
          @foreach($suppliers as $supplier)
          <option value="{{ $supplier->id }}" {{ $selectedSupplier && $selectedSupplier->id == $supplier->id ?
            'selected' : '' }}>
          <option value="{{ $supplier->id }}" {{ $selectedSupplier==$supplier->id ? 'selected' : '' }}>
            {{ $supplier->full_name }}
          </option>
          @endforeach
        </select>
      </div>
    </form>
    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
      <thead>
        <tr>
          <th>N°</th>
          <th>Supplier</th>
          <th>Project Name</th>
          <th>Product</th>
          <th>Payment Method</th>
          <th>Total Price <small>without tax</small></th>
          <th>Total Price <small>with tax</small></th>
          <th>Remaining Amount</th>
          <th>Bills</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($deliveries as $delivery)
        <tr>
          <td>{{$delivery->number}}</td>
          <td>{{$delivery->supplier->full_name}}</td>
          <td><a href="{{route('project.show',['id'=>$delivery->project_id])}}">{{$delivery->project->name}}
            </a> </td>
          <td>
            <i class="ri-file-list-3-line" data-bs-toggle="modal" data-bs-target="#deliveryDetails{{$delivery->id}}"
              style="cursor: pointer;"></i>
            <div class="modal fade" id="deliveryDetails{{$delivery->id}}" tabindex="-1"
              aria-labelledby="deliveryDetailsLabel{{$delivery->id}}" aria-hidden="true">
              <div class="modal-dialog modal-full-width">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="deliveryDetailsLabel{{$delivery->id}}">delivery Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Ref</th>
                          <th>Product Name</th>
                          <th>Category</th>
                          <th>Prix per Unite</th>
                          <th>quantity</th>
                          <th>Total Price</th>
                        </tr>
                      </thead>
                      <tbody id="articleModalBody">
                        @foreach ($delivery->items as $item)
                        <tr>
                          <td>{{$item->ref}}</td>
                          <td>{{$item->name}}</td>
                          <td>{{$item->category}}</td>
                          <td>{{$item->prix_unite}}</td>
                          <td>{{$item->qte}}</td>
                          <td>{{$item->total_price_unite}}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </td>
          <td>{{$delivery->payment_method}}</td>
          <td>
            {{$delivery->total_without_tax}}
          </td>
          <td>
            {{$delivery->total_with_tax}}
          </td>
          <td>
            {{number_format($delivery->remaining_amount, 2)}}
          </td>
          <td>
            <i class="ri-bill-fill" data-bs-toggle="modal" data-bs-target="#billsModal{{$delivery->id}}"
              style="cursor: pointer;"></i>
            <!-- Modal for displaying bills -->
            <div class="modal fade" id="billsModal{{$delivery->id}}" tabindex="-1"
              aria-labelledby="billsModalLabel{{$delivery->id}}" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="billsModalLabel{{$delivery->id}}">Bills for Delivery
                      #{{$delivery->number}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="table-responsive">
                      <table class="table table-striped table-sm">
                        <thead>
                          <tr>
                            <th>Bill Number</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Note</th>
                          </tr>
                        </thead>
                        <tbody>
                          @forelse($delivery->bills as $bill)
                          <tr>
                            <td>{{$bill->bill_number}}</td>
                            <td>{{$bill->bill_date}}</td>
                            <td>{{number_format($bill->amount, 2)}}</td>
                            <td>{{$bill->payment_method}}</td>
                            <td>{{$bill->note}}</td>
                          </tr>
                          @empty
                          <tr>
                            <td colspan="5" class="text-center">No bills found for this delivery.</td>
                          </tr>
                          @endforelse
                        </tbody>
                        <tfoot>
                          <tr>
                            <th colspan="2">{{count($delivery->bills)}} Bills</th>
                            <th>Total Amount: {{number_format($delivery->total_with_tax, 2)}}</th>
                            <th>Total Paid: {{number_format($delivery->total_paid, 2)}}</th>
                            <th>Remaining: {{number_format($delivery->remaining_amount, 2)}}</th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

          </td>
          <td>
            <a href="{{route('delivery.show',['id'=>$delivery->id])}}"><i class="ri-eye-fill"></i></a>
            <i class="ri-bank-card-2-fill" data-bs-toggle="modal" data-bs-target="#addBillModal{{$delivery->id}}"
              style="cursor: pointer;"></i>
            <a href="#" onclick="printInvoice({{ $delivery->id }})"><i class="ri-file-list-fill"></i></a>

            <!-- Modal for adding a bill -->
            <div class="modal fade" id="addBillModal{{$delivery->id}}" tabindex="-1"
              aria-labelledby="addBillModalLabel{{$delivery->id}}" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="addBillModalLabel{{$delivery->id}}">Add Bill for Delivery
                      #{{$delivery->number}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="{{ route('delivery.add-bill', $delivery->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                      <div class="mb-3">
                        <label for="bill_number" class="form-label">Bill Number</label>
                        <input type="text" class="form-control" id="bill_number" name="bill_number" required>
                      </div>
                      <div class="mb-3">
                        <label for="bill_amount" class="form-label">Bill Amount</label>
                        <input type="number" step="0.01" class="form-control" id="bill_amount" name="bill_amount"
                          value="{{$delivery->total_with_tax}}" readonly>
                      </div>
                      <div class="mb-3">
                        <label for="remaining_amount" class="form-label">Remaining Amount</label>
                        <input type="number" step="0.01" class="form-control" id="remaining_amount"
                          name="remaining_amount" value="{{$delivery->remaining_amount}}" readonly>
                      </div>
                      <div class="mb-3">
                        <label for="supplier" class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="supplier" name="supplier"
                          value="{{$delivery->supplier->full_name}}" readonly>
                      </div>
                      <div class="mb-3">
                        <label for="bill_date" class="form-label">Bill Date</label>
                        <input type="date" class="form-control" id="bill_date" name="bill_date" required
                          value="{{date('Y-m-d')}}">
                      </div>
                      <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                      </div>
                      <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                          <option value="bank_transfer">Bank Transfer</option>
                          <option value="cheque">Cheque</option>
                          <option value="cash">Cash</option>
                          <option value="credit_card">Credit Card</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="note" class="form-label">Note</label>
                        <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Add Bill</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <a href="#" class="text-danger" onclick="confirmDelete({{$delivery->id}})"><i
                class="ri-delete-bin-2-fill"></i></a>

            <form id="delete-form-{{$delivery->id}}" action="{{ route('delivery.destroy', $delivery->id) }}"
              method="POST" style="display: none;">
              @csrf
              @method('DELETE')
            </form>
          </td>
        </tr>


        @endforeach
      </tbody>
    </table>
  </div>
</div>
<iframe id="invoiceFrame" style="display:none;"></iframe>

@endsection


@push('scripts')
<script>
  function confirmDelete(id) {
      if (confirm('Are you sure you want to delete this delivary?')) {
          document.getElementById('delete-form-' + id).submit();
      }
  }
</script>
<script>
  function printInvoice(deliveryId) {
        var iframe = document.getElementById('invoiceFrame');
        iframe.style.display = 'block';
        iframe.src = '/dashboard/order/delivery/print/' + deliveryId;
        iframe.onload = function() {
            iframe.contentWindow.print();
            iframe.style.display = 'none';
        };
    }
</script>
<script>
  // generate a random number and append it as value to bill_number
document.addEventListener('DOMContentLoaded', function() {
    var billNumberInput = document.getElementById('bill_number');
    if (billNumberInput) {
      billNumberInput.value= Math.floor(Math.random() * 1000000).toString().padStart(6, '0');
    }
});
</script>
@endpush