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
                        <a href="#expense" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0">
                            Expense
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#task" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0">
                            Task
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="Orders">
                        <h5 class="text-uppercase mb-3"><i class="ri-briefcase-line me-1"></i>Orders
                            {{$project->orders->count()}} </h5>
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive">
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
                                    @if($project->orders->count() == 0)
                                    <tr>
                                        <td colspan="7" class="text-center">No Order found</td>
                                    </tr>
                                    @else
                                    @foreach ($project->orders as $index => $order)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td><a href="{{route('order.show',['id'=>$order->id])}}">{{$order->Ref}}</a>
                                        </td>
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
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            Total Order Cost : {{$project->orders->sum('total_price')}} MAD
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane expense" id="expense">
                        <div class="table-responsive">
                            <h5 class="text-uppercase mb-3"><i class="ri-briefcase-line me-1"></i>Hours
                                {{$project->expenses->count()}} </h5>
                                <table class="table table-striped dt-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Start Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($expenses->count() == 0)
                                    <tr>
                                        <td colspan="7" class="text-center">No expenses found</td>
                                    </tr>
                                    @else
                                    @foreach ($expenses as $expense)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $expense->name }}</td>
                                        <td>{{$expense->type}}
                                            @if($expense->type == 'fix')
                                            <small>({{ $expense->repeat_interval }}) </small>
                                            <i class="ri-file-list-2-fill pointer" data-bs-toggle="modal"
                                                data-bs-target="#infoModal{{$expense->id}}"></i>
                                            <!-- Modal HTML -->
                                            <div class="modal fade" id="infoModal{{$expense->id}}" tabindex="-1"
                                                role="dialog" aria-labelledby="infoModalLabel{{$expense->id}}"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="infoModalLabel{{$expense->id}}">
                                                                Information</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @php
                                                            $exps = App\Models\Expense::where('ref',
                                                            $expense->ref)->get();
                                                            @endphp
                                                            <table class="table table-striped table-centered mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Date</th>
                                                                        <th>Amount</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($exps as $exp)
                                                                    <tr>
                                                                        <td>{{ $loop->iteration }}</td>
                                                                        <td>{{$exp->start_date}}</td>
                                                                        <td>{{$exp->amount}}</td>
                                                                        <td>
                                                                            <a href="#" class="text-danger"
                                                                                onclick="confirmItemDelete({{$exp->id}})"><i
                                                                                    class="ri-delete-bin-2-fill"></i></a>

                                                                            <form id="delete-item-form-{{$exp->id}}"
                                                                                action="{{ route('expense.destroy', $exp->id) }}"
                                                                                method="POST" style="display: none;">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </td>
                                        <td>

                                            {{ $expense->total_amount }}
                                            @if($expense->type == 'fix')
                                            <i class="ri-information-fill" data-bs-toggle="tooltip" data-bs-html="true"
                                                data-bs-title="amount: {{$expense->amount}}<br>repeated:{{$expense->repeat_interval}}"></i>
                                            @endif
                                        </td>
                                        <td>{{ $expense->start_date }}</td>
                                        <td>

                                            <a href="#" class="text-danger" onclick="confirmDelete({{$expense->id}})"><i
                                                    class="ri-delete-bin-2-fill"></i></a>

                                            <form id="delete-form-{{$expense->id}}"
                                                action="{{ route('expense.destroy', $expense->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            Total Expenses Cost : {{$expenses->sum('total_amount')}} MAD
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane task" id="task">
                        <div class="table-responsive">
                            <h5 class="text-uppercase mb-3"><i class="ri-briefcase-line me-1"></i>expenses
                                {{$project->tasks->count()}} </h5>
                                <table class="table table-striped dt-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Employer</th>
                                        <th>Progress</th>
                                        <th>Start Date</th>
                                        <th>Duration</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($project->tasks->count() == 0)
                                    <tr>
                                        <td colspan="7" class="text-center">No Task found</td>
                                    </tr>
                                    @else
                                    @foreach ($project->tasks as $task)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $task->name }}<i class="ri-eye-line" data-bs-toggle="tooltip" data-bs-html="true"
                                                data-bs-title="{{ $task->description }}"></i>
                                        </td>
                                        <td>
                                            @foreach ($task->employees as $employer)
                                            <span class="badge bg-primary">{{ $employer->full_name }}</span>
                                            @endforeach
                                        </td>
                                       
                                        <td>
                                            {{ $task->progress }}%
                                        </td>
                                        <td>
                                            {{ $task->date }}
                                        </td>
                                        <td>
                                            {{ $task->duration }} hours
                                        </td>
                                        <td>
                                            <a href="#" class="text-danger" onclick="confirmDelete({{$task->id}})"><i
                                                    class="ri-delete-bin-2-fill"></i></a>
                
                                            <form id="delete-form-{{$task->id}}" action="{{ route('task.destroy', $task->id) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <a href="#" class="text-primary" data-bs-toggle="modal"
                                                data-bs-target="#editTask{{ $employer->id }}"><i class="ri-edit-fill"></i></a>
                
                                            <!-- Modal for editing an employer -->
                                            <div class="modal fade" id="editTask{{ $employer->id }}" tabindex="-1"
                                                aria-labelledby="editTaskLabel{{ $employer->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editTaskLabel{{ $employer->id }}">
                                                                Edit Employer</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <form id="editEmployerForm{{ $employer->id }}"
                                                            action="{{ route('employee.update', $employer->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="project_id">Project</label>
                                                                    <select class="form-control" id="project_id" name="project_id"
                                                                        required>
                                                                        <option disabled selected>Select a Project</option>
                                                                        @foreach($projects as $project)
                                                                        <option value="{{ $project->id }}" {{$project->id ==
                                                                            $task->project_id ? 'selected' : ''}} >{{ $project->name }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group mb-2">
                                                                    <label for="employer_id">Select Employees</label>
                                                                    <select name="employer_ids[]" id="employer_id"
                                                                        class="select2 form-control select2-multiple"
                                                                        data-toggle="select2" multiple="multiple"
                                                                        data-placeholder="Choose ...">
                                                                        @foreach ($employers as $employer)
                                                                        <option value="{{ $employer->id }}" @foreach ($task->employees
                                                                            as $taskEmployee)
                                                                            {{ $taskEmployee->id == $employer->id ? 'selected' : '' }}
                                                                            @endforeach
                                                                            >{{ $employer->full_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="name">Task Name</label>
                                                                    <input type="text" class="form-control" id="name" name="name"
                                                                        value="{{$task->name}}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="date">Date</label>
                                                                    <input type="date" class="form-control" id="date" name="date"
                                                                        value="{{$task->date}}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="duration">Duration (hours)</label>
                                                                    <input type="number" class="form-control" id="duration"
                                                                        value="{{$task->duration}}" name="duration" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="priority">Priority</label>
                                                                    <input type="number" class="form-control" id="priority"
                                                                        value="{{$task->number}}" name="priority" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="description">Description</label>
                                                                    <textarea class="form-control" id="description"
                                                                        name="description">{{$task->description}}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            Total Hours : {{$project->tasks->sum('total_amount')}} Hr
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection