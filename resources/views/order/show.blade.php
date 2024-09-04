@extends('layouts.app')
@section('title', 'Orders')
@section('content')
<x-Breadcrumb title="Order N°:{{$order->Ref}}" />
<div class="row">
    <div class="card">
    </div>
</div>
@endsection