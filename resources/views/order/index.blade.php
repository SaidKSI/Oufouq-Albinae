@extends('layouts.app')
@section('title', 'Orders')
@section('content')
<x-Breadcrumb title="Orders" />
<div class="row">
    <div class="card">
        <div class="d-flex card-header justify-content-between align-items-center">
            <h4 class="header-title">Orders {{$orderCount}}</h4>
            <div class="dropdown">
                <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="ri-more-2-fill"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">Export</a>
                </div>
            </div>
        </div>
        <div class="col-md-2 mx-3 my-1">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addOrderModal">Add</button>
            <!-- Add Order Modal -->
            <div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addOrderModalLabel">Add Order</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addOrderForm" action="{{ route('order.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="projetc" class="form-label">Project</label>
                                    <select class="form-select" name="project_id">
                                        <option>Select a Project</option>
                                        @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="supplier_id" class="form-label">Supplier</label>
                                    <select class="form-select" id="supplier_id" name="supplier_id">
                                        <option>Select a Suppleir</option>
                                        @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date</label>
                                    <input type="date" class="form-control" id="due_date" name="due_date">
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
                                    <textarea class="form-control" id="description" name="description"></textarea>
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
                        <th>N°</th>
                        <th>Supplier</th>
                        <th>Project Name</th>
                        <th>Items</th>
                        <th>Total Price</th>
                        <th>Paid Amount</th>
                        <th>Remaining</th>
                        <th>Due time</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    <tr>
                        <td><a href="{{route('order.show',['id'=>$order->id])}}" >{{$order->Ref}}</a></td>
                        <td>{{$order->supplier->full_name}}</td>
                        <td>{{$order->project->name}}</td>
                        <td>
                            <i class="ri-file-list-3-line" data-bs-toggle="modal"
                                data-bs-target="#OrderDetails{{$order->id}}" style="cursor: pointer;"></i>
                            <div class="modal fade" id="OrderDetails{{$order->id}}" tabindex="-1"
                                aria-labelledby="OrderDetailsLabel{{$order->id}}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="OrderDetailsLabel{{$order->id}}">Order Details
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
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
                                                            <span class="badge bg-warning text-dark">Pending</span>
                                                            @break
                                                            @case('delivered')
                                                            <span class="badge bg-success text-dark">Pending</span>
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
                        <td>
                            {{$order->total_price}}
                        </td>
                        <td>
                            {{$order->paid_amount}}
                        </td>
                        <td>
                            {{$order->remaining}}
                        </td>
                        <td>
                            {{$order->due_date}}
                        </td>
                        <td>
                            {{$order->created_at}}
                        </td>
                        <td>
                            @switch($order->status)
                            @case('pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                            @break
                            @case('delviered')
                            <span class="badge bg-success text-dark">Delviered</span>
                            @break
                            @endswitch
                        </td>
                        <td>
                            <a href="#" class="text-danger" onclick="confirmDelete({{$order->id}})"><i
                                    class="ri-delete-bin-2-fill"></i></a>

                            <form id="delete-form-{{$order->id}}" action="{{ route('order.destroy', $order->id) }}"
                                method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <a href="{{ route('order.edit', $order->id) }}" class="text-primary"><i
                                    class="ri-edit-fill"></i></a>

                            <a class="text-secondary" data-bs-toggle="modal"
                                data-bs-target="#addPaymentModal{{$order->id}}">
                                <i class="ri-file-edit-fill"></i>
                            </a>
                            <div class="modal fade" id="addPaymentModal{{$order->id}}" tabindex="-1"
                                aria-labelledby="addPaymentModalLabel{{$order->id}}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addPaymentModalLabel{{$order->id}}">Add Payment
                                                for Order N°:{{$order->Ref}}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('payment.store') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <input type="hidden" name="order_id" value="{{$order->id}}">
                                                <div class="mb-3">
                                                    <label for="paid_price" class="form-label">Total Price</label>
                                                    <input type="number" step="0.01" class="form-control"
                                                        value="{{$order->total_price}}" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="paid_price" class="form-label">Paid Amount</label>
                                                    <input type="number" step="0.01" class="form-control"
                                                        value="{{$order->paid_amount}}" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="paid_price" class="form-label">Remaining</label>
                                                    <input type="number" step="0.01" class="form-control"
                                                        value="{{$order->remaining}}" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="paid_price" class="form-label">Paid Price</label>
                                                    <input type="number" step="0.01" class="form-control"
                                                        id="paid_price" name="paid_price" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="payment_method" class="form-label">Payment
                                                        Method</label>
                                                    <select class="form-select" id="payment_method"
                                                        name="payment_method" required>
                                                        <option value="cash">Cash</option>
                                                        <option value="credit_card">Credit Card</option>
                                                        <option value="bank_transfer">Bank Transfer</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="document" class="form-label">Document</label>
                                                    <input type="file" id="document" name="document"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Submit Payment</button>
                                            </div>
                                        </form>
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
@push('styles')

<style>
    sub {
        vertical-align: sub;
        font-size: medium;
    }
</style>
@endpush

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let orderItemIndex = 0;

        // Fetch products and initialize Select2
        function fetchProducts() {
            return $.ajax({
                url: '{{ route('products.index') }}',
                method: 'GET',
                dataType: 'json'
            });
        }

        fetchProducts().then(function(products) {
            $('#addOrderItemBtn').on('click', function () {
                const tableBody = document.querySelector('#orderItemsTable tbody');
                const newRow = document.createElement('tr');

                newRow.innerHTML = `
                    <td>
                        <select class="form-select select" name="order_items[${orderItemIndex}][product_id]" required>
                            <option value="">Select a product</option>
                            ${products.map(product => `<option value="${product.id}">${product.name}</option>`).join('')}
                        </select>
                       
                    </td>
                    
                    <td><input type="number" class="form-control" name="order_items[${orderItemIndex}][quantity]" required></td>
                    <td><input type="number" step="0.01" class="form-control" name="order_items[${orderItemIndex}][price_unit]" required></td>
                    <td><input type="number" step="0.01" class="form-control" name="order_items[${orderItemIndex}][total_price]" readonly></td>
                    <td><button type="button" class="btn btn-danger removeOrderItemBtn">Remove</button></td>
                `;

                tableBody.appendChild(newRow);
                orderItemIndex++;

                // Initialize Select2 for the new product select
                $(newRow).find('.product-select').select2();

                // Add event listener to the remove button
                newRow.querySelector('.removeOrderItemBtn').addEventListener('click', function () {
                    newRow.remove();
                });

                // Add event listener to calculate total price
                const quantityInput = newRow.querySelector(`input[name="order_items[${orderItemIndex - 1}][quantity]"]`);
                const priceUnitInput = newRow.querySelector(`input[name="order_items[${orderItemIndex - 1}][price_unit]"]`);
                const totalPriceInput = newRow.querySelector(`input[name="order_items[${orderItemIndex - 1}][total_price]"]`);

                function calculateTotalPrice() {
                    const quantity = parseFloat(quantityInput.value) || 0;
                    const priceUnit = parseFloat(priceUnitInput.value) || 0;
                    totalPriceInput.value = (quantity * priceUnit).toFixed(2);
                }

                quantityInput.addEventListener('input', calculateTotalPrice);
                priceUnitInput.addEventListener('input', calculateTotalPrice);
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
          // Initialize select2 on all select elements with class 'select2'
          $('.select2').select2();
  
          // Reinitialize select2 when the modal is shown
          $('body').on('shown.bs.modal', '.modal', function () {
              $(this).find('.select2').select2({
                  dropdownParent: $(this).find('.modal-content')
              });
          });
      });
</script>
@endpush