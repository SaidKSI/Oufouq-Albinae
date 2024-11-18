@extends('layouts.app')
@section('title', 'Projects')
@section('content')
<x-Breadcrumb title="Projects" />
<div class="row">
    <div class="card">
        <div class="d-flex card-header justify-content-between align-items-center">
            <h4 class="header-title">Projects {{$projectCount}}</h4>
            <div class="dropdown">
                <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="ri-more-2-fill"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a href="javascript:void(0);" class="dropdown-item">Export</a>
                </div>
            </div>
        </div>
        <div class="col-md-2 mx-3 my-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#addProjectModal">Add</button>
            <!-- Add Project Modal -->
            <div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProjectModalLabel">Add Project</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addProjectForm" action="{{route('project.store')}}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Project Name</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                                <div class="mb-3">
                                    <label for="client" class="form-label">Client</label>
                                    <select class="form-select" id="client_id" name="client_id">
                                        <option disabled selected>Select a Client</option>
                                        @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date">
                                </div>
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date">
                                </div>

                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city">
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-2">
            <table id="basic-datatable" class="table table-striped w-100">
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Client</th>
                        <th>Ref</th>
                        <th>City</th>
                        <th>address</th>
                        <th>Duration</th>
                        <th>Description</th>
                        <th>Created at</th>
                        <th>Status</th>
                        <th>Project Progress</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                    <tr>
                        <td><a href="{{route('project.show',['id'=>$project->id])}}">{{$project->name}}</a></td>
                        <td><a href="#" class="text-dark">{{$project->client->name}}</a></td>
                        <td>{{$project->ref}}</td>
                        <td>{{$project->city}}</td>

                        <td>{{$project->address}}</td>
                        <td>
                            @php
                            $startDate = Carbon\Carbon::parse($project->start_date);
                            $endDate = Carbon\Carbon::parse($project->end_date);
                            $duration = $startDate->diffForHumans($endDate, true);
                            @endphp
                            <span data-bs-toggle="tooltip" data-bs-html="true"
                                data-bs-title="({{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }})">
                                {{ $duration }}
                            </span>
                        </td>
                        <td>
                            <i class="ri-eye-fill" data-bs-toggle="modal" data-bs-target="#projectModal{{$project->id}}"
                                style="cursor: pointer;"></i>

                            <!-- project Modal -->
                            <div class="modal fade" id="projectModal{{$project->id}}" tabindex="-1"
                                aria-labelledby="projectModalLabel{{$project->id}}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="projectModalLabel{{$project->id}}">
                                                {{$project->name}} Description</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{$project->description}}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{$project->created_at->format('d-m-Y')}}</td>
                        <td>
                            @switch($project->status)
                            @case('pending')
                            <span class="badge bg-warning">Pending</span>
                            @break
                            @case('in_progress')
                            <span class="badge bg-secondary text-light">In Progress</span>
                            @break
                            @case('completed')
                            <span class="badge bg-info">Complete</span>
                            @break
                            @endswitch
                        </td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped" role="progressbar"
                                    style="width: {{ $project->progress_percentage }}%"
                                    aria-valuenow="{{ $project->progress_percentage }}" aria-valuemin="0"
                                    aria-valuemax="100">
                                    {{ $project->progress_percentage }}%
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-danger" onclick="confirmDelete({{$project->id}})"><i
                                    class="ri-delete-bin-2-fill"></i></a>

                            <form id="delete-form-{{$project->id}}"
                                action="{{ route('project.destroy', $project->id) }}" method="POST"
                                style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <a href="#" class="text-primary" data-bs-toggle="modal"
                                data-bs-target="#editprojectModal{{$project->id}}"><i class="ri-edit-fill"></i></a>

                            <!-- Edit project Modal -->
                            <div class="modal fade" id="editprojectModal{{$project->id}}" tabindex="-1"
                                aria-labelledby="editprojectModalLabel{{$project->id}}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editprojectModalLabel{{$project->id}}">Edit
                                                project {{$project->name}}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form id="editprojectForm{{$project->id}}"
                                            action="{{route('project.update', $project->id)}}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Project Name</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        value="{{$project->name}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="client" class="form-label">Client</label>
                                                    <select class="form-select" name="client_id">
                                                        <option disabled selected>Select a Client</option>
                                                        @foreach($clients as $client)
                                                        <option value="{{ $client->id }}" {{$client->id ==
                                                            $project->client_id ? 'selected' : ''}}>{{ $client->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="ref" class="form-label">Reference</label>
                                                    <input type="text" class="form-control" id="ref" name="ref"
                                                        value="{{$project->ref}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="city" class="form-label">City</label>
                                                    <input type="text" class="form-control" id="city" name="city"
                                                        value="{{$project->city}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Address</label>
                                                    <input type="text" class="form-control" id="address" name="address"
                                                        value="{{$project->address}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="start_date" class="form-label">Start Date</label>
                                                    <input type="date" class="form-control" id="start_date"
                                                        name="start_date" value="{{$project->start_date}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="end_date" class="form-label">End Date</label>
                                                    <input type="date" class="form-control" id="end_date"
                                                        name="end_date" value="{{$project->end_date}}">
                                                </div>
                                                <div class="card-body">
                                                    <h4 class="header-title">Project Progress</h4>
                                                    <input type="text" id="range_02" data-plugin="range-slider"
                                                        data-min="0" data- data-from="{{$project->progress_percentage}}"
                                                        name="progress_percentage"
                                                        value="{{$project->progress_percentage}}" />
                                                </div>
                                                <div class="form-floating">
                                                    <textarea class="form-control" placeholder="Leave a comment here"
                                                        id="floatingTextarea" style="height: 100px"
                                                        name="description">{{$project->description}}</textarea>
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

@push('scripts')
<script src="{{ asset('assets/vendor/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/component.range-slider.js') }}"></script>
<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this project?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>

@endpush