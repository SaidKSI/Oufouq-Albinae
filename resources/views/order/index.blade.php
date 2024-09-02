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
                                    <label for="order_id" class="form-label">order</label>
                                    <select class="form-select" id="order_id" name="order_id">
                                        <option>Select a order</option>
                                        @foreach($orders as $order)
                                        <option value="{{ $order->id }}">{{ $order->full_name }}</option>
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
                                                <th>Name</th>
                                                <th>Unit</th>
                                                <th>Quantity</th>
                                                <th>Price per Unit</th>
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
                        <td><a href="#" class="text-dark">{{$order->Ref}}</a></td>
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
                                                        <th>Name</th>
                                                        <th>Prix per Unite</th>
                                                        <th>quantity</th>
                                                        <th>Unite</th>
                                                        <th>Total Price</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="articleModalBody">
                                                    @foreach ($order->items as $item)
                                                    <tr>
                                                        <td>{{$item->name}}</td>
                                                        <td>{{$item->unit}}</td>
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
                            <i class="ri-eye-fill" data-bs-toggle="modal" data-bs-target="#orderModal{{$order->id}}"
                                style="cursor: pointer;"></i>

                            <!-- order Modal -->
                            <div class="modal fade" id="orderModal{{$order->id}}" tabindex="-1"
                                aria-labelledby="orderModalLabel{{$order->id}}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="orderModalLabel{{$order->id}}">
                                                {{$order->full_name}}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{$order->description}}

                                            <ul class="social-list list-inline mt-3 mb-0">
                                                <li class="list-inline-item">
                                                    <a href="https://www.facebook.com/{{$order->facebook_handle}}"
                                                        target="_blank"
                                                        class="social-list-item border-primary text-primary">
                                                        <i class="ri-facebook-circle-fill"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="https://www.linkedin.com/in/{{$order->linkedin_handle}}"
                                                        target="_blank"
                                                        class="social-list-item border-primary text-primary">
                                                        <i class="ri-linkedin-fill"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="https://twitter.com/{{$order->twitter_handle}}"
                                                        target="_blank" class="social-list-item border-info text-info">
                                                        <i class="ri-twitter-fill"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="https://www.instagram.com/{{$order->instagram_handle}}"
                                                        target="_blank"
                                                        class="social-list-item border-danger text-danger">
                                                        <i class="ri-instagram-fill"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{$order->address}}
                        </td>
                        <td>
                            {{$order->rating}}
                        </td>
                        <td>
                            {{$order->created_at}}
                        </td>
                        <td>
                            <a href="#" class="text-danger" onclick="confirmDelete({{$order->id}})"><i
                                    class="ri-delete-bin-2-fill"></i></a>

                            <form id="delete-form-{{$order->id}}" action="{{ route('order.destroy', $order->id) }}"
                                method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <a href="#" class="text-primary" data-bs-toggle="modal"
                                data-bs-target="#editorderModal{{$order->id}}"><i class="ri-edit-fill"></i></a>

                            <!-- Edit order Modal -->
                            <div class="modal fade" id="editorderModal{{$order->id}}" tabindex="-1"
                                aria-labelledby="editorderModalLabel{{$order->id}}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editorderModalLabel{{$order->id}}">Edit
                                                order</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form id="editorderForm{{$order->id}}"
                                            action="{{route('order.update', $order->id)}}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="full_name" class="form-label">Full Name</label>
                                                    <input type="text" class="form-control" id="full_name"
                                                        name="full_name" value="{{$order->full_name}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="ice" class="form-label">ICE</label>
                                                    <input type="text" class="form-control" id="ice" name="ice"
                                                        value="{{$order->ice}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="phone" class="form-label">Phone</label>
                                                    <input type="text" class="form-control" id="phone" name="phone"
                                                        value="{{$order->phone}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email"
                                                        value="{{$order->email}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="city" class="form-label">City</label>
                                                    <input type="text" class="form-control" id="city" name="city"
                                                        value="{{$order->city}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Address</label>
                                                    <input type="text" class="form-control" id="address" name="address"
                                                        value="{{$order->address}}">
                                                </div>

                                                <div class="row">
                                                    <h3 class="text-center">Social Media</h3>
                                                    <div class="col">
                                                        <div class="mb-3">
                                                            <input type="text" class="form-control" id="facebook"
                                                                name="facebook" placeholder="Facebook"
                                                                value="{{$order->facebook_handle}}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="text" class="form-control" id="twitter"
                                                                name="twitter" placeholder="Twitter"
                                                                value="{{$order->twitter_handle}}">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="mb-3">
                                                            <input type="text" class="form-control" id="linkedin"
                                                                name="linkedin" placeholder="Linkedin"
                                                                value="{{$order->linkedin_handle}}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="text" class="form-control" id="instagram"
                                                                name="instagram" placeholder="Instagram"
                                                                value="{{$order->instagram_handle}}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-floating">
                                                    <textarea class="form-control" placeholder="Leave a comment here"
                                                        id="floatingTextarea" style="height: 100px"
                                                        name="description">{{$order->description}}</textarea>
                                                    <label for="floatingTextarea">Description</label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
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

        document.getElementById('addOrderItemBtn').addEventListener('click', function () {
            const tableBody = document.querySelector('#orderItemsTable tbody');
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td><input type="text" class="form-control" name="order_items[${orderItemIndex}][name]" required></td>
                <td>
                    <select class="form-select" name="order_items[${orderItemIndex}][unit]" required>
                        <option value="pieces">pieces</option>
                        <option value="kg">kg</option>
                        <option value="liters">liters</option>
                        <option value="m2">m<sub>2</sub></option>
                        <option value="m3">m<sub>3</sub></option>
                    </select>
                </td>
                <td><input type="number" class="form-control" name="order_items[${orderItemIndex}][quantity]" required></td>
                <td><input type="number" step="0.01" class="form-control" name="order_items[${orderItemIndex}][price_unit]" required></td>
                <td><input type="number" step="0.01" class="form-control" name="order_items[${orderItemIndex}][total_price]" readonly></td>
                <td><button type="button" class="btn btn-danger removeOrderItemBtn">Remove</button></td>
            `;

            tableBody.appendChild(newRow);
            orderItemIndex++;

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
</script>
@endpush