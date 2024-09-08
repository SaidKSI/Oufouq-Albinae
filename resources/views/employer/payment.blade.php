@extends('layouts.app')
@section('title', 'Employers Payments')
@section('content')
<x-Breadcrumb title="Employers Payments" />
<div class="row">
  <div class="card">
    <div class="col-md-5">

      <a class="btn btn-outline-primary m-2" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
        <i class="ri-file-edit-fill"></i> Employer Payment
      </a>
      <!-- Add Payment Modal -->
      <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="{{ route('employee.storePayment') }}" method="POST">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="addPaymentModalLabel">Add Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label for="employee_id" class="form-label">Employee</label>
                  <select class="form-select" id="employee_id" name="employee_id" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                  <label for="paid_price" class="form-label">Paid Price</label>
                  <input type="number" class="form-control" id="paid_price" name="paid_price" required>
                </div>
                <div class="mb-3">
                  <label for="remaining" class="form-label">Remaining Wage</label>
                  <input type="number" class="form-control" id="remaining" name="remaining" readonly>
                </div>
                <div class="mb-3">
                  <label for="payment_method" class="form-label">Payment
                    Method</label>
                  <select class="form-select" id="payment_method" name="payment_method" required>
                    <option value="cash">Cash</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="bank_transfer">Bank Transfer</option>
                  </select>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add Payment</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="col">

      <h5 class="text-uppercase mx-2 mb-3 mt-2"><i class="ri-briefcase-line me-1"></i>Payment
        {{$payments->count()}} </h5>
      <div class="table-responsive">
        <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
          <thead>
            <tr>
              <th>#</th>
              <th>Employer</th>
              <th>Payment ID</th>
              <th>Paid Amount</th>
              <th>Remaining </th>
              <th>Payment Method</th>
              <th>Date</th>
              <th>Doc</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($payments as $index => $payment)
            <tr>
              <td>{{$index + 1}}</td>
              <td>{{$payment->employee->full_name}}</td>
              <td>{{$payment->payment_id}}</td>
              <td>{{$payment->paid_price}}</td>
              <td>{{$payment->remaining}}</td>
              <td>{{$payment->payment_method}}</td>
              <td>{{$payment->created_at->format('d-m-Y')}}</td>
              <td>
                <a href="{{route('employee.invoice',['id'=>$payment->id])}}" target="_blank"><i
                    class="ri-file-text-fill"></i></a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
        const employeeSelect = document.getElementById('employee_id');
        const remainingInput = document.getElementById('remaining');

        employeeSelect.addEventListener('change', function() {
            const employeeId = this.value;

            if (employeeId) {
                fetch(`/dashboard/employees/${employeeId}/total-wage`)
                    .then(response => response.json())
                    .then(data => {
                        remainingInput.value = data.total_wage;
                    })
                    .catch(error => {
                        console.error('Error fetching total wage:', error);
                        remainingInput.value = '';
                    });
            } else {
                remainingInput.value = '';
            }
        });
    });
</script>
@endpush