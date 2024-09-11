@extends('layouts.app')
@section('title', 'Orders')
@section('content')
<x-Breadcrumb title="Order N°:{{$order->Ref}}" />
<div class="row">
    <div class="col-xl-5 col-lg-5">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="mb-1 mt-2">Order N°:{{$order->Ref}}</h4>

                <p class="text-muted mt-3">
                    @switch($order->status)
                    @case('pending')
                    <span class="badge bg-warning text-dark fs-4">Pending</span>
                    @break
                    @case('completed')
                    <span class="badge bg-success text-dark fs-4">Delivered</span>
                    @break
                    @endswitch
                </p>
                <div class="text-center fs-4 mt-3">
                    <p class="text-muted mb-2"><strong>Project :</strong> <span
                            class="ms-2">{{$order->project->name}}</span>
                    </p>
                    <p class="text-muted mb-2"><strong>Supplier :</strong><span
                            class="ms-2">{{$order->supplier->full_name}}</span></p>
                    <p class="text-muted mb-2"><strong>Total Price :</strong> <span
                            class="ms-2 ">{{$order->total_price}}</span></p>
                    <p class="text-muted mb-1"><strong>Paid Amount :</strong> <span
                            class="ms-2">{{$order->payments->sum('paid_price')}}</span></p>
                    <p class="text-muted mb-1"><strong>Remaining :</strong> <span
                            class="ms-2">{{$order->remaining}}</span></p>
                </div>
                @if($order->status == 'completed')
                <div class="col text-center fs-3 mt-2">
                    <h4 class="text-center">Delivered Document</h4>
                    <a class="ms-2" href="{{asset('storage/' .$order->documents->first()->path)}}" target="_blank"><i
                            class="ri-file-text-fill"></i></a>
                </div>
                @endif
                <div class="text-start mt-3">
                    <h3 class="text-center">Order Item {{$order->items->count()}}</h3>
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
    <div class="col">
        <div class="card">
            <h5 class="text-uppercase mx-2 mb-3 mt-2"><i class="ri-briefcase-line me-1"></i>Payment
                {{$order->payments->count()}} </h5>
            <div class="table-responsive">
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Payment ID</th>
                            <th>Paid Amount</th>
                            <th>Remaining </th>
                            <th>Payment Method</th>
                            <th>Date</th>
                            <th>Doc</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->payments as $index => $payment)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$payment->payment_id}}</td>
                            <td>{{$payment->paid_price}}</td>
                            <td>{{$payment->remaining}}</td>
                            <td>{{$payment->payment_method}}</td>
                            <td>{{$payment->created_at->format('d-m-Y')}}</td>
                            <td>
                                <a href="{{asset('storage/' .$payment->documents->first()->path)}}" target="_blank"><i
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