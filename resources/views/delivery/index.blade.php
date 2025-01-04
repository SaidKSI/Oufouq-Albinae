@extends('layouts.app')
@section('title', 'Delivery')
@section('content')
<x-Breadcrumb title="{{ $type === 'supplier' ? 'Bon de Livraison Fournisseur' : 'Bon de Livraison Client' }}" />
<div class="row">
  <div class="card">
    <div class="col-md-2 mx-3 my-1">
      <a href="#" onclick="showTaxTypeModal()" class="btn btn-outline-primary">Create invoice</a>
    </div>
    <form method="GET" action="{{ route('delivery.index', ['type' => $type]) }}" class="m-3">
      <div class="form-group w-25">
        <label for="filter_id">Filter by {{ $type === 'supplier' ? 'Supplier' : 'Client' }}</label>
        <select name="{{ $type === 'supplier' ? 'supplier_id' : 'client_id' }}" id="filter_id"
          class="form-control w-25 m-2 select2 w-50">
          <option value="">All {{ $type === 'supplier' ? 'Suppliers' : 'Clients' }}</option>
          @foreach($filterEntity as $entity)
          <option value="{{ $entity->id }}" {{ $selectedEntity==$entity->id ? 'selected' : '' }}>
            {{ $type === 'supplier' ? $entity->full_name : $entity->name }}
          </option>
          @endforeach
        </select>
      </div>
    </form>
    <table id="basic-datatable" class="table table-striped dt-responsive w-100">
      <thead>
        <tr>
          <th>N°</th>
          <th>{{ $type === 'supplier' ? 'Supplier' : 'Client' }}</th>
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
          <td style="font-size: 0.8rem">{{ $type === 'supplier' ? $delivery->supplier->full_name :
            $delivery->project->client->name }}</td>
          <td style="font-size: 0.8rem">{{$delivery->project->name}}</td>
          <td>
            <i class="ri-file-list-3-line" data-bs-toggle="modal" data-bs-target="#deliveryDetails{{$delivery->id}}"
              style="cursor: pointer;"></i>
            <div class="modal fade" id="deliveryDetails{{$delivery->id}}" tabindex="-1"
              aria-labelledby="deliveryDetailsLabel{{$delivery->id}}" aria-hidden="true">
              <div class="modal-dialog modal-lg">
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
            <div class="dropdown">
              <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Actions
              </button>
              <ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item" href="{{route('delivery.show',['id'=>$delivery->id])}}">
                    <i class="ri-eye-line"></i> View
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('delivery.edit', $delivery->id) }}">
                    <i class="ri-edit-line"></i> Edit
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="#" data-bs-toggle="modal"
                    data-bs-target="#addBillModal{{$delivery->id}}">
                    <i class="ri-bank-card-2-fill"></i> Add Bill
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('delivery.print', $delivery->id) }}" target="_blank">
                    <i class="ri-file-fill"></i> Print
                  </a>
                </li>
                <li>
                  <a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{$delivery->id}})">
                    <i class="ri-delete-bin-2-fill"></i> Delete
                  </a>
                </li>
              </ul>
            </div>

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

<div class="modal fade" id="taxTypeModal" tabindex="-1" aria-labelledby="taxTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="taxTypeModalLabel">Select Tax Calculation Type</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="taxTypeForm" action="{{ route('delivery.invoice',['type'=>$type]) }}" method="GET">
          <input type="hidden" name="type" value="{{$type}}">
          <div class="mb-3">
            <label for="tax_type" class="form-label">Tax Type</label>
            <select class="form-select" id="tax_type" name="tax_type" required>
              <option value="normal">Normal Tax (20% added to total)</option>
              <option value="included">Tax Included (20% calculated from total)</option>
              <option value="no_tax">No Tax</option>
            </select>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-primary">Continue</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    // Initialize Select2 on the select element
    $('#filter_id').select2({
      placeholder: "Select an option",
      allowClear: true
    });

    // Submit the form on change
    $('#filter_id').on('change', function() {
      this.form.submit();
    });
  });
</script>
<script>
  function confirmDelete(id) {
      if (confirm('Are you sure you want to delete this delivary?')) {
          document.getElementById('delete-form-' + id).submit();
      }
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
<script>
  function showTaxTypeModal() {
    var modal = new bootstrap.Modal(document.getElementById('taxTypeModal'));
    modal.show();
}
</script>
@endpush