@extends('layouts.app')
@section('title', 'Stock')
@section('content')
<x-Breadcrumb title="Stock" />
<div class="row">
    <div class="card">
        <div class="d-flex card-header justify-content-between align-items-center">
            <h4 class="header-title">Stock</h4>
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
        <div class="card-body p-2">
            <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $index => $product)
                    <tr>
                        <td>{{$index + 1 }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->orderItems->sum('quantity') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection