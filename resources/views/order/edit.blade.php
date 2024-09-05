@extends('layouts.app')
@section('title', 'Orders')
@section('content')
<x-Breadcrumb title="Order N°:{{$order->Ref}}" />
<div class="row">
    <div class="card">
        <div class="col-md-8 offset-2 my-3">
            <form id="editOrderForm{{$order->id}}" action="{{ route('order.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="project_id" class="form-label">Project</label>
                        <select class="form-select" name="project_id">
                            <option>Select a Project</option>
                            @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ $order->project_id == $project->id ? 'selected' :
                                '' }}>{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Supplier</label>
                        <select class="form-select" id="supplier_id" name="supplier_id">
                            <option>Select a Supplier</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $order->supplier_id == $supplier->id ? 'selected'
                                : '' }}>{{ $supplier->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Order Items</label>
                        <table class="table table-bordered" id="orderItemsTable{{$order->id}}">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Price per Unit</th>
                                    <th>Total Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $index => $item)
                                <tr>
                                    <td>
                                        <select class="form-select select" name="order_items[{{ $index }}][product_id]"
                                            required onchange="updateUnit(this, {{ $index }})">
                                            <option value="">Select a product</option>
                                            @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-unit="{{ $product->unit }}" {{
                                                $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name
                                                }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                  
                                    <td><input type="number" name="order_items[{{ $index }}][quantity]"
                                            class="form-control" value="{{ $item->quantity }}" required></td>
                                    <td><input type="number" step="0.01" name="order_items[{{ $index }}][price_unit]"
                                            class="form-control" value="{{ $item->price_unit }}" required></td>
                                    <td><input type="number" step="0.01" name="order_items[{{ $index }}][total_price]"
                                            class="form-control" value="{{ $item->total_price }}" required></td>
                                    <td><button type="button" class="btn btn-danger remove-order-item">Remove</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-secondary add-order-item-btn"
                            id="addOrderItemBtn{{$order->id}}">Add Item</button>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description"
                            name="description">{{ $order->description }}</textarea>
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
@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.add-order-item-btn').forEach(function(button) {
        button.addEventListener('click', function () {
            var orderId = this.id.replace('addOrderItemBtn', '');
            var tableBody = document.querySelector('#orderItemsTable' + orderId + ' tbody');
            var newRow = document.createElement('tr');
            var index = tableBody.querySelectorAll('tr').length;

            newRow.innerHTML = `
                <td><input type="text" name="order_items[${index}][name]" class="form-control" required></td>
                <td><input type="text" name="order_items[${index}][unit]" class="form-control" required></td>
                <td><input type="number" name="order_items[${index}][quantity]" class="form-control" required></td>
                <td><input type="number" step="0.01" name="order_items[${index}][price_unit]" class="form-control" required></td>
                <td><input type="number" step="0.01" name="order_items[${index}][total_price]" class="form-control" required></td>
                <td><button type="button" class="btn btn-danger remove-order-item">Remove</button></td>
            `;

            tableBody.appendChild(newRow);

            // Add event listener to the remove button
            newRow.querySelector('.remove-order-item').addEventListener('click', function () {
                newRow.remove();
            });
        });
    });

    document.querySelectorAll('.remove-order-item').forEach(function(button) {
        button.addEventListener('click', function () {
            this.closest('tr').remove();
        });
    });
});
</script>
@endpush