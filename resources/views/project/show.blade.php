@extends('layouts.app')
@section('title', 'Projects')
@section('content')
<x-Breadcrumb title="Projects N°: {{$project->ref}}" />
<div class="row">
    <div class="col-xl-5 col-lg-5">
        <div class="card text-center">
            <div class="card-body">
                <div>
                    <i class="ri-add-box-fill btn btn-outline-primary mx-1" data-bs-toggle="modal"
                        data-bs-target="#add"></i>

                    <!-- Add Modal -->
                    <div class="modal fade" id="add" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addLabel">Add Item</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label for="addType">Select Type</label>
                                        <select class="form-select" id="addType" onchange="showForm()">
                                            <option selected disabled>Select what to add</option>
                                            <option value="task">Task</option>
                                            <option value="expense">Expense</option>
                                            <option value="order">Order</option>
                                        </select>
                                    </div>

                                    <!-- Add Task Form -->
                                    <form id="addTaskForm" action="{{ route('task.store') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                        <div class="form-group mb-2">
                                            <label for="employer_id">Select Employees</label>
                                            <select name="employer_ids[]" id="employer_id"
                                                class="select2 form-control select2-multiple" data-toggle="select2"
                                                multiple="multiple" data-placeholder="Choose ...">
                                                @foreach ($employers as $employer)
                                                <option value="{{ $employer->id }}">{{ $employer->full_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Task Name</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="date">Date</label>
                                            <input type="date" class="form-control" id="date" name="date" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="duration">Duration (hours)</label>
                                            <input type="number" class="form-control" id="duration" name="duration"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="priority">Priority</label>
                                            <input type="number" class="form-control" id="priority" name="priority"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description"
                                                name="description"></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>

                                    <!-- Add Expense Form -->
                                    <form id="addExpenseForm" action="{{ route('expense.store') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="start_date" class="form-label">Start Date</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="expense-type" class="form-label">Type</label>
                                            <select class="form-select" id="expense-type" name="type" required>
                                                <option selected disabled>Select the Expense Type</option>
                                                <option value="fix">Fix</option>
                                                <option value="variable">Variable</option>
                                            </select>
                                        </div>
                                        <div class="mb-3" id="fix-inputs" style="display: none">
                                            <label class="form-label">Repeat</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" placeholder="repeat interval"
                                                    name="repeat_interval">
                                                <select class="form-select" id="duration" name="duration" required>
                                                    <option selected disabled>Select the Duration</option>
                                                    <option value="daily">Every Day</option>
                                                    <option value="weekly">Every Week</option>
                                                    <option value="monthly">Every Month</option>
                                                    <option value="yearly">Every Year</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="amount" class="form-label">Amount</label>
                                            <input type="number" class="form-control" id="amount" name="amount"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description"
                                                rows="3"></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>

                                    <!-- Add Order Form -->
                                    <form id="addOrderForm" action="{{ route('order.store') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                        <div class="mb-3">
                                            <label for="supplier_id" class="form-label">Supplier</label>
                                            <select class="form-select" id="supplier_id" name="supplier_id" required>
                                                <option selected disabled>Select a Supplier</option>
                                                @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->full_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Order Items</label>
                                            <table class="table table-bordered" id="orderItemsTable">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Quantity</th>
                                                        <th>Price per Unit</th>
                                                        <th>Total Price</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Order items will be added here dynamically -->
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-secondary" id="addOrderItemBtn">Add
                                                Item</button>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description"
                                                name="description"></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h4 class="mb-1 mt-2">Project N°:{{$project->ref}}</h4>
                <p class="text-muted mt-3">
                    @switch($project->status)
                    @case('pending')
                    <span class="badge bg-warning text-dark fs-4">Pending</span>
                    @break
                    @case('completed')
                    <span class="badge bg-success text-dark fs-4">Delivered</span>
                    @break
                    @endswitch
                </p>
                <div class="text-center fs-4 mt-3">
                    <p class="text-muted mb-2"><strong>Project :</strong> <span class="ms-2">{{$project->name}}</span>
                    </p>
                    <p class="text-muted  mb-2"><strong>Description :</strong><span
                            class="ms-2">{{$project->description}}</span></p>
                    <p class="text-muted  mb-2"><strong>City :</strong><span class="ms-2">{{$project->city}}</span></p>
                    <p class="text-muted  mb-2"><strong>Adddress :</strong><span
                            class="ms-2">{{$project->address}}</span></p>
                    @php
                    $startDate = Carbon\Carbon::parse($project->start_date);
                    $endDate = Carbon\Carbon::parse($project->end_date);
                    $duration = $startDate->diffForHumans($endDate, true);
                    @endphp
                    <p class="text-muted  mb-2"><strong>Duration :</strong><span class="ms-2" data-bs-toggle="tooltip"
                            data-bs-html="true"
                            data-bs-title="({{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }})">{{$duration}}</span>
                    </p>
                    <p class="text-muted  mb-2"><strong>Adddress :</strong><span
                            class="ms-2">{{$project->address}}</span></p>
                    <p class="text-muted  mb-2"><strong>Created at :</strong><span
                            class="ms-2">{{$project->created_at->format('d-m-Y')}}</span></p>

                </div>
                <div class="progress my-2">
                    <div class="progress-bar progress-bar-striped" role="progressbar"
                        style="width: {{ $project->progress_percentage }}%"
                        aria-valuenow="{{ $project->progress_percentage }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $project->progress_percentage }}%
                    </div>
                </div>
                {{-- <div class="d-flex justify-content-center align-items-center fs-3">
                    Estimate Cost : <a href="{{ route('projects.invoice', $project->id) }}" target="_blank"><i
                            class="ri-file-list-fill"></i></a>
                </div> --}}
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                    <li class="nav-item">
                        <a href="#Orders" data-bs-toggle="tab" aria-expanded="false"
                            class="nav-link rounded-start rounded-0 active">
                            Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#expense" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0">
                            Expense
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#task" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0">
                            Task
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="Orders">
                        <h5 class="text-uppercase mb-3">Orders
                            {{$project->orders->count()}} </h5>
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive">
                                <thead class="border-top border-bottom bg-light-subtle border-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Order Ref</th>
                                        <th>Supplier</th>
                                        <th>Products</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($project->orders->count() == 0)
                                    <tr>
                                        <td colspan="7" class="text-center">No Order found</td>
                                    </tr>
                                    @else
                                    @foreach ($project->orders as $index => $order)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td><a href="{{route('order.show',['id'=>$order->id])}}">{{$order->Ref}}</a>
                                        </td>
                                        <td>{{$order->supplier->full_name}}</td>
                                        <td>
                                            <i class="ri-file-list-3-line" data-bs-toggle="modal"
                                                data-bs-target="#OrderDetails{{$order->id}}"
                                                style="cursor: pointer;"></i>
                                            <div class="modal fade" id="OrderDetails{{$order->id}}" tabindex="-1"
                                                aria-labelledby="OrderDetailsLabel{{$order->id}}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="OrderDetailsLabel{{$order->id}}">Order Details
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Product Name</th>
                                                                        <th>Unite</th>
                                                                        <th>quantity</th>
                                                                        <th>Prix per Unite</th>
                                                                        <th>Total Price</th>
                                                                        <th>Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="articleModalBody">
                                                                    @foreach ($order->items as $item)
                                                                    <tr>
                                                                        <td>{{$item->product->name}}</td>
                                                                        <td>{{$item->product->unit}}</td>
                                                                        <td>{{$item->quantity}}</td>
                                                                        <td>{{$item->price_unit}}</td>
                                                                        <td>{{$item->total_price}}</td>

                                                                        <td>
                                                                            @switch($item->status)
                                                                            @case('pending')
                                                                            <span
                                                                                class="badge bg-warning text-dark">Pending</span>
                                                                            @break
                                                                            @case('delivered')
                                                                            <span
                                                                                class="badge bg-success text-dark">Delivered</span>
                                                                            @break
                                                                            @endswitch
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$order->total_price}}</td>
                                        <td>
                                            @switch($order->status)
                                            @case('pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                            @break
                                            @case('completed')
                                            <span class="badge bg-success text-dark">Delivered</span>
                                            @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            Total Order Cost : {{$project->orders->sum('total_price')}} MAD
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane expense" id="expense">
                        <div class="table-responsive">
                            <h5 class="text-uppercase mb-3"><i class="ri-briefcase-line me-1"></i>Hours
                                {{$project->expenses->count()}} </h5>
                            <table class="table table-striped dt-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Start Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($expenses->count() == 0)
                                    <tr>
                                        <td colspan="7" class="text-center">No expenses found</td>
                                    </tr>
                                    @else
                                    @foreach ($expenses as $expense)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $expense->name }}</td>
                                        <td>{{$expense->type}}
                                            @if($expense->type == 'fix')
                                            <small>({{ $expense->repeat_interval }}) </small>
                                            <i class="ri-file-list-2-fill pointer" data-bs-toggle="modal"
                                                data-bs-target="#infoModal{{$expense->id}}"></i>
                                            <!-- Modal HTML -->
                                            <div class="modal fade" id="infoModal{{$expense->id}}" tabindex="-1"
                                                role="dialog" aria-labelledby="infoModalLabel{{$expense->id}}"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="infoModalLabel{{$expense->id}}">
                                                                Information</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @php
                                                            $exps = App\Models\Expense::where('ref',
                                                            $expense->ref)->get();
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
                                                                            <a href="#" class="text-danger"
                                                                                onclick="confirmItemDelete({{$exp->id}})"><i
                                                                                    class="ri-delete-bin-2-fill"></i></a>

                                                                            <form id="delete-item-form-{{$exp->id}}"
                                                                                action="{{ route('expense.destroy', $exp->id) }}"
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

                                            <form id="delete-form-{{$expense->id}}"
                                                action="{{ route('expense.destroy', $expense->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            Total Expenses Cost : {{$expenses->sum('total_amount')}} MAD
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane task" id="task">
                        <div class="table-responsive">
                            <h5 class="text-uppercase mb-3"><i class="ri-briefcase-line me-1"></i>expenses
                                {{$project->tasks->count()}}</h5>
                            <table class="table table-striped dt-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Employer</th>
                                        <th>Progress</th>
                                        <th>Start Date</th>
                                        <th>Duration</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($project->tasks as $task)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $task->name }}<i class="ri-eye-line" data-bs-toggle="tooltip"
                                                data-bs-html="true" data-bs-title="{{ $task->description }}"></i>
                                        </td>
                                        <td>
                                            <a href="{{route('project.show',['id'=>$task->project_id])}}">{{
                                                $task->project->name }}</a>

                                        </td>
                                        <td>
                                            @foreach ($task->employees as $employer)
                                            <span class="badge bg-primary">{{ $employer->full_name }}</span>
                                            @endforeach
                                        </td>


                                        <td>
                                            {{ $task->date }}
                                        </td>
                                        <td>
                                            {{ $task->duration }} hours
                                        </td>
                                        <td>
                                            <div class="progress ">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $task->progress }}%;"
                                                    aria-valuenow="{{ $task->progress }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    {{$task->progress }}%</div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#" class="text-danger" onclick="confirmDelete({{$task->id}})"><i
                                                    class="ri-delete-bin-2-fill"></i></a>

                                            <form id="delete-form-{{$task->id}}"
                                                action="{{ route('task.destroy', $task->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7" class="text-center">Total Hours :
                                            {{$project->tasks->sum('duration')}} Hr</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('assets/vendor/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
<script src="{{asset('assets/js/pages/component.range-slider.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#add').on('shown.bs.modal', function () {
            $('.select2').select2({
                dropdownParent: $('#add'),
                allowClear: true
            });
        });

    });
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
<script>
    function showForm() {
        var selectedType = document.getElementById('addType').value;
        document.getElementById('addTaskForm').style.display = 'none';
        document.getElementById('addExpenseForm').style.display = 'none';
        document.getElementById('addOrderForm').style.display = 'none';

        if (selectedType === 'task') {
            document.getElementById('addTaskForm').style.display = 'block';
        } else if (selectedType === 'expense') {
            document.getElementById('addExpenseForm').style.display = 'block';
        } else if (selectedType === 'order') {
            document.getElementById('addOrderForm').style.display = 'block';
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Show/hide repeat interval fields based on expense type
    document.getElementById('expense-type').addEventListener('change', function () {
        const fixInputs = document.getElementById('fix-inputs');
        if (this.value === 'fix') {
            fixInputs.style.display = 'block';
        } else {
            fixInputs.style.display = 'none';
        }
    });

    // Add order item dynamically
    document.getElementById('addOrderItemBtn').addEventListener('click', function () {
        const tableBody = document.querySelector('#orderItemsTable tbody');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td><input type="text" name="products[]" class="form-control" required></td>
            <td><input type="number" name="quantities[]" class="form-control" required></td>
            <td><input type="number" name="prices[]" class="form-control" required></td>
            <td><input type="number" name="total_prices[]" class="form-control" readonly></td>
            <td><button type="button" class="btn btn-danger remove-order-item-btn">Remove</button></td>
        `;

        tableBody.appendChild(newRow);

        // Add event listener to remove button
        newRow.querySelector('.remove-order-item-btn').addEventListener('click', function () {
            newRow.remove();
        });

        // Calculate total price
        newRow.querySelector('input[name="quantities[]"]').addEventListener('input', calculateTotalPrice);
        newRow.querySelector('input[name="prices[]"]').addEventListener('input', calculateTotalPrice);

        function calculateTotalPrice() {
            const quantity = newRow.querySelector('input[name="quantities[]"]').value;
            const price = newRow.querySelector('input[name="prices[]"]').value;
            const totalPrice = newRow.querySelector('input[name="total_prices[]"]');
            totalPrice.value = quantity * price;
        }
    });
});
</script>
@endpush