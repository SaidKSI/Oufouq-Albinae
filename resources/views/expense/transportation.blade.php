@extends('layouts.app')
@section('title', 'Transportation Expenses')
@section('content')
<x-Breadcrumb title="Transportation Expenses" />
<div class="row">
    <div class="card">
        <div class="col-md-2 mx-3 my-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#addTransportationExpenseModal">Add</button>
            <!-- Add trans expense Modal -->
            <div class="modal fade" id="addTransportationExpenseModal" tabindex="-1"
                aria-labelledby="addTransportationExpenseModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addTransportationExpenseModalLabel">Add Transportation Expense
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addTransportationExpenseForm" action="{{ route('transportation-expenses.store') }}"
                            method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="project_id" class="form-label">Project</label>
                                    <select class="form-select" id="project_id" name="project_id" required>
                                        @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="product_id" class="form-label">Product</label>
                                    <input type="text" class="form-control" id="product" name="product" required>
                                </div>
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" min="0">
                                </div>
                                <div class="mb-3">
                                    <label for="highway_expense" class="form-label">Highway Expense</label>
                                    <input type="number" step="0.01" class="form-control" id="highway_expense"
                                        name="highway_expense" min="0">
                                </div>
                                <div class="mb-3">
                                    <label for="gaz_expense" class="form-label">Gaz Expense</label>
                                    <input type="number" step="0.01" class="form-control" id="gaz_expense"
                                        name="gaz_expense" min="0">
                                </div>
                                <div class="mb-3">
                                    <label for="other_expense" class="form-label">Other Expense</label>
                                    <input type="number" step="0.01" class="form-control" id="other_expense"
                                        name="other_expense" min="0">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description"
                                        rows="3"></textarea>
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
        <div class="card-body p-2">
            <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Project</th>
                        <th>highway expense</th>
                        <th>gaz expense</th>
                        <th>other expense</th>
                        <th>total</th>
                        <th>description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $expense)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $expense->product }}</td>
                        <td>
                            <span data-bs-toggle="tooltip" data-bs-html="true"
                                data-bs-title="City: {{$expense->project->city}}">{{ $expense->project->name }}</span>
                        </td>
                        <td>{{ $expense->highway_expense }}</td>
                        <td>{{ $expense->gaz_expense }}</td>
                        <td>{{ $expense->other_expense }}</td>
                        <td>{{ $expense->total_expense }}</td>
                        <td>
                            <i class="ri-eye-fill" data-bs-toggle="modal" data-bs-target="#expenseModal{{$expense->id}}"
                                style="cursor: pointer;"></i>

                            <!-- expense Modal -->
                            <div class="modal fade" id="expenseModal{{$expense->id}}" tabindex="-1"
                                aria-labelledby="expenseModalLabel{{$expense->id}}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="expenseModalLabel{{$expense->id}}">
                                                {{$expense->ref}} Description</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{$expense->description}}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
    $(document).ready(function() {
        $('#project_id').select2({
            dropdownParent: $('.modal-body'),
            placeholder: "Select a Project",
            allowClear: true
        }).on('select2:open', function(e) {
            $('.select2-container--open').css('z-index', 1060);
        });
    });
    $(document).ready(function() {
        $('#product_id').select2({
            dropdownParent: $('.modal-body'),
            placeholder: "Select a Product",
            allowClear: true
        }).on('select2:open', function(e) {
            $('.select2-container--open').css('z-index', 1060);
        });
    });
</script>
@endpush