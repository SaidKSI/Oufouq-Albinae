@extends('layouts.app')

@section('content')
<x-Breadcrumb title="Dashboard" />
<div class="row">
  <div class="col-xl-6">
    <div class="card">
      <div class="card-body">
        <div dir="ltr">
          <div id="line-chart-zoomable" class="apex-charts" data-colors="#fa5c7c"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-6">
    <div class="card">
      <h4 class="text-center m-1">Tasks</h4>
      <div class="card-body my-3">
        @if($tasks->count() > 0)
        @foreach ($tasks as $task)
        <div class="row justify-content-sm-between">
          <div class="col-sm-6 mb-2 mb-sm-0">
            <div class="form-check">
              <input type="checkbox" class="form-check-input task-checkbox" id="task{{ $task->id }}"
                data-id="{{ $task->id }}" {{ $task->status == 'complete' ? 'checked' : '' }}>
              <label
                class="form-check-label text-center {{ $task->status == 'complete' ? 'text-decoration-line-through' : '' }}"
                for="task{{ $task->id }}">
                {{$task->name}} <br>
                <small>{{$task->description}}</small>
              </label>
            </div> <!-- end checkbox -->
          </div> <!-- end col -->
          <div class="col-sm-6">
            <div class="d-flex justify-content-end">
              <div id="tooltip-container">
                @foreach ($task->employees as $employer)
                <span class="badge bg-primary">{{ $employer->full_name }}</span>
                @endforeach
                <div>
                  <ul class="list-inline fs-13 text-end">
                    <li class="list-inline-item">
                      <i class="ri-calendar-todo-line fs-16 me-1"></i> {{$task->created_at->diffForHumans()}}
                    </li>
                    <li class="list-inline-item text-end ms-1">
                      <span class="badge bg-danger-subtle text-danger p-1">{{$task->priority}}</span>
                    </li>
                  </ul>
                </div>
              </div> <!-- end .d-flex-->
            </div> <!-- end col -->
          </div>
          @endforeach
          @else
          <div class="text-center">
            <p>No tasks available</p>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  <!-- Devis Not Converted to Bon de Livraison -->
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h4>Devis Not Converted to Bon de Livraison</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped dt-responsive nowrap w-100">
            <thead>
              <tr>
                <th>Reference</th>
                <th>Client</th>
                <th>Project</th>
                <th>Date</th>
                <th>Total HT</th>
                <th>TVA</th>
                <th>Total TTC</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($notConvertedToDelivery as $estimate)
              <tr>
                <td>{{ $estimate->number }}</td>
                <td>{{ $estimate->project->client->name }}</td>
                <td>{{ $estimate->project->name }}</td>
                <td>{{ $estimate->created_at->format('d/m/Y') }}</td>
                <td>{{ number_format($estimate->total_without_tax, 2) }}</td>
                <td>{{ number_format($estimate->tax, 2) }}</td>
                <td>{{ number_format($estimate->total_with_tax, 2) }}</td>
                <td>
                  <button type="button" class="btn btn-sm btn-primary"
                    onclick="openDeliveryModal('{{ $estimate->id }}')">
                    Convert to BL
                  </button>
                  <a href="{{ route('estimate.print', $estimate->id) }}" class="btn btn-sm btn-info" target="_blank">
                    <i class="ri-printer-line"></i>
                  </a>
                  <form id="delivery-form-{{$estimate->id}}" action="{{ route('estimate.to.delivery', $estimate->id) }}"
                    method="POST" style="display: none;">
                    @csrf
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" class="text-center">No unconverted estimates found</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- Devis Not Converted to Facture -->
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h4>Devis Not Converted to Facture</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped dt-responsive nowrap w-100">
            <thead>
              <tr>
                <th>Reference</th>
                <th>Client</th>
                <th>Project</th>
                <th>Total HT</th>
                <th>TVA</th>
                <th>Total TTC</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($notConvertedToFacture as $delivery)
              <tr>
                <td>{{ $delivery->number }}</td>
                <td>{{ $delivery->project->client->name }}</td>
                <td>{{ $delivery->project->name }}</td>
                <td>{{ number_format($delivery->total_without_tax, 2) }}</td>
                <td>{{ number_format($delivery->tax, 2) }}</td>
                <td>{{ number_format($delivery->total_with_tax, 2) }}</td>
                <td>
                  <button type="button" class="btn btn-sm btn-primary"
                    onclick="openFactureModal('{{ $delivery->id }}')">
                    Convert to Facture
                  </button>
                  <a href="{{ route('delivery.print', $delivery->id) }}" class="btn btn-sm btn-info" target="_blank">
                    <i class="ri-printer-line"></i>
                  </a>
                  <form id="facture-form-{{$delivery->id}}" action="{{ route('estimate.to.facture', $delivery->id) }}"
                    method="POST" style="display: none;">
                    @csrf
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center">No unconverted delivery notes found</td>
              </tr>
              @endforelse
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
              <input type="text" class="form-control" id="reference" name="reference">
            </div>
            <div class="mb-3">
              <label for="payment_method" class="form-label">Payment Method</label>
              <select class="form-select" id="payment_method" name="payment_method" required
                onchange="toggleTransactionId('facture')">
                <option value="">Select payment method</option>
                <option value="cash">Cash</option>
                <option value="check">Check</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="credit">Credit</option>
                <option value="traita">Traita</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="mb-3" id="transaction_id_div">
              <label for="transaction_id" class="form-label">Transaction ID</label>
              <input type="text" class="form-control" id="transaction_id" name="transaction_id">
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
              <label for="delivery_reference" class="form-label">Reference</label>
              <input type="text" class="form-control" id="delivery_reference" name="reference">
            </div>
            <div class="mb-3">
              <label for="delivery_payment_method" class="form-label">Payment Method</label>
              <select class="form-select" id="delivery_payment_method" name="payment_method" required
                onchange="toggleTransactionId('delivery')">
                <option value="">Select payment method</option>
                <option value="cash">Cash</option>
                <option value="check">Check</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="credit">Credit</option>
                <option value="traita">Traita</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="mb-3" id="delivery_transaction_id_div">
              <label for="delivery_transaction_id" class="form-label">Transaction ID</label>
              <input type="text" class="form-control" id="delivery_transaction_id" name="transaction_id">
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
</div>


@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/dragula/dragula.min.js') }}"></script>
<!-- Apex Charts js -->
<script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
      fetch('{{ route('company.capital.history') }}')
          .then(response => response.json())
          .then(data => {
              const dates = data.map(entry => entry.date);
              const capital = data.map(entry => entry.capital);

              var options = {
                  chart: {
                      type: 'line',
                      height: 380,
                      zoom: { enabled: true },
                  },
                  series: [{
                      name: 'Company Capital',
                      data: capital
                  }],
                  xaxis: {
                      categories: dates,
                      type: 'datetime',
                      title: { text: 'Date' }
                  },
                  yaxis: {
                      title: { text: 'Capital' },
                      labels: {
                          formatter: function (value) {
                              return value.toFixed(2);
                          }
                      }
                  },
                  title: { text: 'Company Capital History', align: 'left' },
                  grid: {
                      row: { colors: ['transparent', 'transparent'], opacity: 0.2 },
                      borderColor: '#f1f3fa',
                  },
                  stroke: { width: [3], curve: 'smooth' },
                  markers: { size: 0, style: 'full' },
                  fill: {
                      gradient: {
                          enabled: true,
                          shadeIntensity: 1,
                          inverseColors: false,
                          opacityFrom: 0.5,
                          opacityTo: 0.1,
                          stops: [0, 70, 80, 100],
                      },
                  },
                  tooltip: {
                      shared: false,
                      y: {
                          formatter: function (value) {
                              return value.toFixed(2);
                          }
                      }
                  },
                  responsive: [
                      {
                          breakpoint: 600,
                          options: {
                              chart: { toolbar: { show: false } },
                              legend: { show: false },
                          },
                      },
                  ],
              };

              var chart = new ApexCharts(document.querySelector("#line-chart-zoomable"), options);
              chart.render();
          })
          .catch(error => console.error('Error fetching capital history:', error));
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.task-checkbox').forEach(function (checkbox) {
      checkbox.addEventListener('change', function () {
        var taskId = this.getAttribute('data-id');
        var isChecked = this.checked;
        var status = isChecked ? 'complete' : 'pending';

        fetch(`/dashboard/tasks/${taskId}/update-status`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ status: status })
        }).then(response => response.json())
          .then(data => {
            if (data.success) {
              var label = document.querySelector(`label[for="task${taskId}"]`);
              if (isChecked) {
                label.classList.add('text-decoration-line-through');
              } else {
                label.classList.remove('text-decoration-line-through');
              }
            } else {
              console.error('Failed to update task status');
            }
          }).catch(error => console.error('Error:', error));
      });
    });
  });
</script>
<script>
  function openDeliveryModal(estimateId) {
    document.getElementById('delivery_estimate_id').value = estimateId;
    new bootstrap.Modal(document.getElementById('deliveryModal')).show();
}

function openFactureModal(deliveryId) {
    document.getElementById('estimate_id').value = deliveryId;
    new bootstrap.Modal(document.getElementById('factureModal')).show();
}

function toggleTransactionId(type) {
    const paymentMethod = document.getElementById(type + '_payment_method').value;
    const transactionIdDiv = document.getElementById(type + '_transaction_id_div');
    
    if (paymentMethod === 'cash' || paymentMethod === '') {
        transactionIdDiv.style.display = 'none';
    } else {
        transactionIdDiv.style.display = 'block';
    }
}

function submitDeliveryConversion() {
    const estimateId = document.getElementById('delivery_estimate_id').value;
    const form = document.getElementById('delivery-form-' + estimateId);
    
    // Add form data
    const formData = new FormData(document.getElementById('deliveryDetailsForm'));
    for (let pair of formData.entries()) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = pair[0];
        input.value = pair[1];
        form.appendChild(input);
    }
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('deliveryModal')).hide();
    
    // Submit form in new tab and refresh current page
    form.target = '_blank';
    form.submit();
    
    // Refresh the current page after a short delay
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

function submitFactureConversion() {
    const deliveryId = document.getElementById('estimate_id').value;
    const form = document.getElementById('facture-form-' + deliveryId);
    
    // Add form data
    const formData = new FormData(document.getElementById('factureDetailsForm'));
    for (let pair of formData.entries()) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = pair[0];
        input.value = pair[1];
        form.appendChild(input);
    }
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('factureModal')).hide();
    
    // Submit form in new tab and refresh current page
    form.target = '_blank';
    form.submit();
    
    // Refresh the current page after a short delay
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}
</script>

@endpush