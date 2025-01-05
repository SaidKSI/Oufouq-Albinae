@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Project Header -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">{{ $project->name }}</h3>
            <span class="badge bg-{{ $project->status === 'completed' ? 'success' : 'primary' }}">
                {{ ucfirst($project->status) }}
            </span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Description:</strong> {{ $project->description }}</p>
                    <p><strong>Progress:</strong> {{ $project->progress_percentage }}%</p>
                    <p><strong>Start Date:</strong> {{ $project->start_date }}</p>
                    <p><strong>End Date:</strong> {{ $project->end_date }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>City:</strong> {{ $project->city }}</p>
                    <p><strong>Address:</strong> {{ $project->address }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Client Information -->
    <div class="card mb-4">
        <div class="card-header">
            <h4>Client Information</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Name:</strong> {{ $project->client->name }}</p>
                    <p><strong>Email:</strong> {{ $project->client->email }}</p>
                    <p><strong>Phone:</strong> {{ $project->client->phone }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>ICE:</strong> {{ $project->client->ice }}</p>
                    <p><strong>Address:</strong> {{ $project->client->address }}</p>
                    <p><strong>City:</strong> {{ $project->client->city }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Overview -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Financial Overview</h4>
                </div>
                <div class="card-body">
                    <p><strong>Total Estimate Amount:</strong> {{ number_format($project->getTotalEstimateAmount(), 2)
                        }}</p>
                    <p><strong>Total Paid Amount:</strong> {{ number_format($project->getTotalPaidAmount(), 2) }}</p>
                    <p><strong>Remaining Amount:</strong> {{ number_format($project->getRemainingAmount(), 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Expenses Overview</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($project->expenses as $expense)
                                <tr>
                                    <td>{{ $expense->type }}</td>
                                    <td>{{ number_format($expense->amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks -->
    <div class="card mb-4">
        <div class="card-header">
            <h4>Tasks</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Progress</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($project->tasks as $task)
                        <tr>
                            <td>{{ $task->name }}</td>
                            <td>{{ $task->progress }}%</td>
                            <td>{{ $task->priority }}</td>
                            <td>{{ $task->status }}</td>
                            <td>{{ $task->duration }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Deliveries and Factures -->
    <div class="card mb-4">
        <div class="card-header">
            <h4>Deliveries & Factures</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Date</th>
                            <th>Total Without Tax</th>
                            <th>Tax</th>
                            <th>Total With Tax</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($project->deliveries as $delivery)
                        <tr>
                            <td>{{ $delivery->number }}</td>
                            <td>{{ $delivery->date }}</td>
                            <td>{{ number_format($delivery->total_without_tax, 2) }}</td>
                            <td>{{ number_format($delivery->tax, 2) }}</td>
                            <td>{{ number_format($delivery->total_with_tax, 2) }}</td>
                            <td>
                                @if($delivery->hasFacture())
                                <span class="badge bg-success">Factured</span>
                                @else
                                <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Transportation Expenses -->
    <div class="card mb-4">
        <div class="card-header">
            <h4>Transportation Expenses</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Quantity</th>
                            <th>Highway Expense</th>
                            <th>Gas Expense</th>
                            <th>Other Expense</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($project->transportationExpenses as $expense)
                        <tr>
                            <td>{{ $expense->quantity }}</td>
                            <td>{{ number_format($expense->highway_expense, 2) }}</td>
                            <td>{{ number_format($expense->gaz_expense, 2) }}</td>
                            <td>{{ number_format($expense->other_expense, 2) }}</td>
                            <td>{{ $expense->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection