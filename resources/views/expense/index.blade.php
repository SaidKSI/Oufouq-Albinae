@extends('layouts.app')
@section('title', 'Expenses - Fix | Variable')
@section('content')
<x-Breadcrumb title="Expenses - Fix | Variable" />
<div class="row">
  <div class="card">
    <div class="col-md-3 m-2">
      <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addExpense">Add</button>
      <!-- Add trans expense Modal -->
      <div class="modal fade" id="addExpense" tabindex="-1" aria-labelledby="addExpenseLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addExpenseLabel">Add Transportation Expense
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addExpenseForm" action="{{ route('expense.store') }}" method="POST">
              @csrf
              <div class="modal-body">
                <div class="mb-3">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" class="form-control" id="name" name="name" rows="3" />
                </div>
                <div class="mb-3">
                  <label for="target" class="form-label">Target</label>
                  <select class="form-select" id="target" name="target" required>
                    <option selected disabled> Select the Expense Target</option>
                    <option value="company">Company</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                  <label for="start_date" class="form-label">Start Date</label>
                  <input type="date" step="0.01" class="form-control" id="start_date" name="start_date">
                </div>
                <div class="mb-3">
                  <label for="expense-type" class="form-label">Target</label>
                  <select class="form-select" id="expense-type" name="type" required>
                    <option selected disabled> Select the Expense Type</option>
                    <option value="fix">Fix</option>
                    <option value="variable">Variable</option>
                  </select>
                </div>

                <div class="mb-3" id="fix-inputs" style="display: none">
                  <label class="form-label">Repeat <i class="ri-information-fill" data-bs-toggle="tooltip"
                      data-bs-html="true" data-bs-title="How many times the expense will be Repeated"></i> </label>
                  <div class="input-group">
                    <input type="number" class="form-control" placeholder="repeat interval" aria-label=""
                      aria-describedby="basic-addon1" name="repeat_interval" >
                    <select class="form-select" id="duration" name="duration" required>
                      <option selected disabled> Select the Duration</option>
                      <option value="daily">Every Day</option>
                      <option value="weekly">Every Week</option>
                      <option value="monthly">Every Month</option>
                      <option value="yearly">Every Year</option>
                    </select>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="amount" class="form-label">Amount</label>
                  <input type="number" class="form-control" id="amount" name="amount" rows="3" />
                </div>
                <div class="mb-3">
                  <label for="description" class="form-label">Description</label>
                  <textarea class="form-control" id="description" name="description" rows="3"></textarea>
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
    <div class="row mx-4 my-4">
      <table id="basic-datatable" class="table table-striped dt-responsive">
        <thead>
          <tr>
            <th>#</th>
            <th>Target</th>
            <th>Name</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Start Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($expenses as $expense)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $expense->project->name ?? 'Company' }}</td>
            <td>
              {{ $expense->name }}
              <i class="ri-eye-line" data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $expense->description }}"></i>

            </td>
            <td>{{$expense->type}}
              @if($expense->type == 'fix')
              <small>({{ $expense->repeat_interval }}) </small>
              <i class="ri-file-list-2-fill pointer" data-bs-toggle="modal"
                data-bs-target="#infoModal{{$expense->id}}"></i>
              <!-- Modal HTML -->
              <div class="modal fade" id="infoModal{{$expense->id}}" tabindex="-1" role="dialog"
                aria-labelledby="infoModalLabel{{$expense->id}}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="infoModalLabel{{$expense->id}}">Information</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      @php
                      $exps = App\Models\Expense::where('ref', $expense->ref)->get();
                      @endphp
                      <table class="table table-striped table-centered mb-0">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($exps as $exp)
                          <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$exp->start_date}}</td>
                            <td>{{$exp->amount}}</td>
                            <td>
                              <a href="#" class="text-danger" onclick="confirmItemDelete({{$exp->id}})"><i
                                  class="ri-delete-bin-2-fill"></i></a>

                              <form id="delete-item-form-{{$exp->id}}" action="{{ route('expense.destroy', $exp->id) }}"
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
                </div>
              </div>
              @endif
            </td>
            <td>
            
              {{ $expense->total_amount }}
              @if($expense->type == 'fix')
              <i class="ri-information-fill" data-bs-toggle="tooltip" data-bs-html="true"
                data-bs-title="amount: {{$expense->amount}}<br>repeated:{{$expense->repeat_interval}}"></i>
              @endif
            </td>
            <td>{{ $expense->start_date }}</td>
            <td>
         
              <a href="#" class="text-danger" onclick="confirmDelete({{$expense->id}})"><i
                class="ri-delete-bin-2-fill"></i></a>

            <form id="delete-form-{{$expense->id}}" action="{{ route('expense.destroy', $expense->id) }}"
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
</div>
@endsection

@push('scripts')
<script>
  function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this Expense?')) {
        document.getElementById('delete-form-' + id).submit();
    }
  }
</script>
<script>
  function confirmItemDelete(id) {
    if (confirm('Are you sure you want to delete this Expense Item?')) {
        document.getElementById('delete-item-form-' + id).submit();
    }
  }
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
        const expenseTypeSelect = document.getElementById('expense-type');
        const fixInputsDiv = document.getElementById('fix-inputs');

        expenseTypeSelect.addEventListener('change', function() {
            if (this.value === 'fix') {
                fixInputsDiv.style.display = 'block';
            } else {
                fixInputsDiv.style.display = 'none';
            }
        });
    });
</script>
@endpush