@extends('layouts.app')
@section('title', 'Invoice Details')
@section('content')
<x-Breadcrumb title="Invoice Details" />
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Invoice #{{ $invoice->number }}</h4>
                <!-- Add invoice details here -->
                <div class="mt-4">
                    <a href="{{ route('invoice.print', $invoice->id) }}" class="btn btn-primary" target="_blank">Print Invoice</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
