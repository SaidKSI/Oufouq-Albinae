@extends('layouts.app')
@section('title', 'Project Estimate')
@section('content')
<x-Breadcrumb title="Project Estimate" />
<div class="row">
  <div class="card">
    <div class="card-body p-2">
      <div class="col-md-2 m-1">
        <a href="{{route('project-estimate.create-invoice')}}" class="btn btn-outline-primary">Create Estimate
          Invoice</a>
      </div>
      <div class="col-md-12">
        <table id="basic-datatable" class="table table-striped dt-responsive">
          <thead>
            <tr>
              <th scope="col">Client</th>
              <th scope="col">Project</th>
              <th scope="col">Items</th>
              <th scope="col">Total Price <small>(without tax)</small></th>
              <th scope="col">Tax</th>
              <th scope="col">Total Amount <small>(with tax)</small> </th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($estimates as $estimate)

            <tr>
              <td>{{ $estimate->project->client->name }}</td>
              <td>{{ $estimate->project->name }}</td>
              <td>
                <button type="button" class="btn btn-sm btn-info show-items" data-bs-toggle="modal"
                  data-bs-target="#itemsModal_{{ $estimate->id }}">
                  <i class="ri-eye-line"></i>
                </button>
                <div class="modal fade" id="itemsModal_{{ $estimate->id }}" tabindex="-1"
                  aria-labelledby="itemsModalLabel_{{ $estimate->id }}" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="itemsModalLabel_{{ $estimate->id }} ">Estimate Items</h5>
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
                            @foreach ($estimate->items as $item)
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
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td>{{ number_format($estimate->total_without_tax,2) }}</td>
              <td>{{ number_format($estimate->tax,2) }}</td>
              <td>
                {{ number_format($estimate->total_with_tax, 2) }}
              </td>
              <td>
                <div class="dropdown">
                  <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Actions
                  </button>
                  <ul class="dropdown-menu">
                    <li>
                      <a class="dropdown-item" href="#" onclick="confirmDelete({{$estimate->id}})">
                        <i class="ri-delete-bin-2-fill"></i> Delete
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="{{ route('estimate.print', $estimate->id) }}" target="_blank">
                        <i class="ri-file-fill"></i> Print Estimate
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#" onclick="convertToFacture({{$estimate->id}})">
                        <i class="ri-bill-fill"></i> Convert to Invoice
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#" onclick="convertToDelivery({{$estimate->id}})">
                        <i class="ri-truck-fill"></i> Convert to Delivery
                      </a>
                    </li>
                  </ul>
                </div>

                <form id="delete-form-{{$estimate->id}}" action="{{ route('estimate.destroy', $estimate->id) }}"
                  method="POST" style="display: none;">
                  @csrf
                  @method('DELETE')
                </form>

                <form id="facture-form-{{$estimate->id}}" action="{{ route('estimate.to.facture', $estimate->id) }}"
                  method="POST" style="display: none;" target="_blank">
                  @csrf
                </form>

                <form id="delivery-form-{{$estimate->id}}" action="{{ route('estimate.to.delivery', $estimate->id) }}"
                  method="POST" style="display: none;" target="_blank">
                  @csrf
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Add this modal at the bottom of your blade file -->
<div class="modal fade" id="factureModal" tabindex="-1" aria-labelledby="factureModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="factureModalLabel">Convert to Invoice</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="factureDetailsForm">
          <input type="hidden" id="estimate_id" name="estimate_id">
          <div class="mb-3">
            <label for="reference" class="form-label">Reference</label>
            <input type="text" class="form-control" id="reference" name="reference" required>
          </div>
          <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method</label>
            <select class="form-select" id="payment_method" name="payment_method" required>
              <option value="">Select payment method</option>
              <option value="cash">Cash</option>
              <option value="check">Check</option>
              <option value="bank_transfer">Bank Transfer</option>
              <option value="credit">Credit</option>
              <option value="traita">Traita</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="transaction_id" class="form-label">Transaction ID</label>
            <input type="text" class="form-control" id="transaction_id" name="transaction_id" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="submitFactureConversion()">Convert</button>
      </div>
    </div>
  </div>
</div>

<!-- Add this modal for delivery conversion -->
<div class="modal fade" id="deliveryModal" tabindex="-1" aria-labelledby="deliveryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deliveryModalLabel">Convert to Delivery Note</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="deliveryDetailsForm">
          <input type="hidden" id="delivery_estimate_id" name="estimate_id">
          <div class="mb-3">
            <label for="delivery_payment_method" class="form-label">Payment Method</label>
            <select class="form-select" id="delivery_payment_method" name="payment_method" required>
              <option value="">Select payment method</option>
              <option value="cash">Cash</option>
              <option value="check">Check</option>
              <option value="bank_transfer">Bank Transfer</option>
              <option value="credit">Credit</option>
              <option value="traita">Traita</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="delivery_transaction_id" class="form-label">Transaction ID</label>
            <input type="text" class="form-control" id="delivery_transaction_id" name="transaction_id" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="submitDeliveryConversion()">Convert</button>
      </div>
    </div>
  </div>
</div>

@endsection


@push('scripts')

<script>
  function confirmDelete(id) {
      if (confirm('Are you sure you want to delete this estimate?')) {
          document.getElementById('delete-form-' + id).submit();
      }
  }

  function convertToFacture(id) {
      // Set the estimate ID in the modal form
      document.getElementById('estimate_id').value = id;
      // Show the modal
      var modal = new bootstrap.Modal(document.getElementById('factureModal'));
      modal.show();
  }

  function submitFactureConversion() {
      const estimateId = document.getElementById('estimate_id').value;
      const paymentMethod = document.getElementById('payment_method').value;
      const transactionId = document.getElementById('transaction_id').value;
      const reference = document.getElementById('reference').value;
      // Validate form
      if (!paymentMethod || !transactionId || !reference) {
          alert('Please fill in all fields');
          return;
      }

      // Get the form element
      const form = document.getElementById('facture-form-' + estimateId);
      
      // Add payment details to the form
      const paymentMethodInput = document.createElement('input');
      paymentMethodInput.type = 'hidden';
      paymentMethodInput.name = 'payment_method';
      paymentMethodInput.value = paymentMethod;
      form.appendChild(paymentMethodInput);

      const transactionIdInput = document.createElement('input');
      transactionIdInput.type = 'hidden';
      transactionIdInput.name = 'transaction_id';
      transactionIdInput.value = transactionId;
      form.appendChild(transactionIdInput);

      const referenceInput = document.createElement('input');
      referenceInput.type = 'hidden';
      referenceInput.name = 'reference';
      referenceInput.value = reference;
      form.appendChild(referenceInput);

      // Close the modal
      bootstrap.Modal.getInstance(document.getElementById('factureModal')).hide();

      // Submit the form
      form.submit();
  }

  function convertToDelivery(id) {
      // Set the estimate ID in the modal form
      document.getElementById('delivery_estimate_id').value = id;
      // Show the modal
      var modal = new bootstrap.Modal(document.getElementById('deliveryModal'));
      modal.show();
  }

  function submitDeliveryConversion() {
      const estimateId = document.getElementById('delivery_estimate_id').value;
      const paymentMethod = document.getElementById('delivery_payment_method').value;
      const transactionId = document.getElementById('delivery_transaction_id').value;

      // Validate form
      if (!paymentMethod || !transactionId) {
          alert('Please fill in all fields');
          return;
      }

      // Get the form element
      const form = document.getElementById('delivery-form-' + estimateId);
      
      // Add payment details to the form
      const paymentMethodInput = document.createElement('input');
      paymentMethodInput.type = 'hidden';
      paymentMethodInput.name = 'payment_method';
      paymentMethodInput.value = paymentMethod;
      form.appendChild(paymentMethodInput);

      const transactionIdInput = document.createElement('input');
      transactionIdInput.type = 'hidden';
      transactionIdInput.name = 'transaction_id';
      transactionIdInput.value = transactionId;
      form.appendChild(transactionIdInput);

      // Close the modal
      bootstrap.Modal.getInstance(document.getElementById('deliveryModal')).hide();

      // Submit the form
      form.submit();
  }
</script>
@endpush