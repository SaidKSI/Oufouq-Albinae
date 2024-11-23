@extends('layouts.app')
@section('title', 'Supplier')

@section('content')
<x-Breadcrumb title="Supplier Details" />

<div class="card">
    <div class="card-header">
        <h4>{{ $supplier->full_name }}</h4>
    </div>
    <div class="card-body">
        <!-- Basic supplier info -->
        <div class="row mb-4">
            <div class="col-md-6">
                <p><strong>Email:</strong> {{ $supplier->email }}</p>
                <p><strong>Phone:</strong> {{ $supplier->phone }}</p>
                <p><strong>Address:</strong> {{ $supplier->address }}, {{ $supplier->city }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>ICE:</strong> {{ $supplier->ice }}</p>
                <p><strong>Description:</strong> {{ $supplier->description }}</p>
            </div>
        </div>

        <!-- Deliveries section -->
        <div class="card">
            <div class="card-header">
                <h5>Deliveries History</h5>
            </div>
            <div class="card-body">
                <!-- Date filter form -->
                <form method="GET" class="row mb-3">
                    <div class="col-md-4">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}"
                            placeholder="Start Date">
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}"
                            placeholder="End Date">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('supplier.show', $supplier->id) }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>

                <!-- Deliveries table -->
                <div class="table-responsive">
                    <table id="basic-datatable" class="table table-striped dt-responsive">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reference</th>
                                <th>Total With Tax</th>
                                <th>Total Without Tax</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($deliveries as $delivery)
                            <tr>
                                <td>{{ $delivery->created_at->format('Y-m-d') }}</td>
                                <td>{{ $delivery->number }}</td>
                                <td>{{ number_format($delivery->total_with_tax, 2) }} DH</td>
                                <td>{{ number_format($delivery->total_without_tax, 2) }} DH</td>
                                <td>
                                    <span class="badge bg-{{ $delivery->getRemainingAmountAttribute() === 0 ? 'success' : 'warning' }}">
                                        {{ ucfirst($delivery->getRemainingAmountAttribute() === 0 ? 'paid' : 'unpaid') }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('delivery.show', $delivery->id) }}"
                                        class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No deliveries found</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-end"><strong>Total with tax:</strong></td>
                                <td colspan="4"><strong>{{ number_format($deliveries->sum('total_with_tax'), 2) }}
                                        DH</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-end"><strong>Total without tax:</strong></td>
                                <td colspan="4"><strong>{{ number_format($deliveries->sum('total_without_tax'), 2) }}
                                        DH</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection