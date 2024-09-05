@extends('layouts.app')
@section('title', 'Projects')
@section('content')
<x-Breadcrumb title="Projects N°: {{$project->ref}}" />
<div class="row">
    <div class="col-xl-5 col-lg-5">
        <div class="card text-center">
            <div class="card-body">
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
                <div class="progress mt-2">
                    <div class="progress-bar progress-bar-striped" role="progressbar"
                        style="width: {{ $project->progress_percentage }}%"
                        aria-valuenow="{{ $project->progress_percentage }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $project->progress_percentage }}%
                    </div>
                </div>
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
                        <a href="#timeline" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0">
                            Orders
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="Orders">
                        <h5 class="text-uppercase mb-3"><i class="ri-briefcase-line me-1"></i>Orders {{$project->orders->count()}} </h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-centered table-hover table-borderless mb-0">
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
                                    @foreach ($project->orders as $index => $order)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td><a href="{{route('order.show',['id'=>$order->id])}}">{{$order->Ref}}</a></td>
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection