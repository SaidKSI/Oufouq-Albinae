@extends('layouts.app')
@section('title', 'Task')
@section('content')
<x-Breadcrumb title="Task" />
<div class="row">
    <div class="card">
        <div class="col-md-3 m-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addTask">Add</button>
            <!-- Add trans expense Modal -->
            <div class="modal fade" id="addTask" tabindex="-1" aria-labelledby="addTaskLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addTaskLabel">Add Transportation Expense
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addTaskForm" action="{{ route('task.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="project_id">Project</label>
                                    <select class="form-control" id="project_id" name="project_id" required>
                                        <option disabled selected>Select a Project</option>
                                        @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="employer_id">Select Employees</label>
                                    <select name="employer_ids[]" id="employer_id"
                                        class="select2 form-control select2-multiple" data-toggle="select2"
                                        multiple="multiple" data-placeholder="Choose ...">
                                        @foreach ($employers as $employer)
                                        <option value="{{ $employer->id }}">{{ $employer->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">Task Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <div class="form-group">
                                    <label for="duration">Duration (hours)</label>
                                    <input type="number" class="form-control" id="duration" name="duration" required>
                                </div>
                                <div class="form-group">
                                    <label for="priority">Priority</label>
                                    <input type="number" class="form-control" id="priority" name="priority" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
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
        <div class="col mx-4 my-4">
            <table id="basic-datatable" class="table table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Project</th>
                        <th>Employer</th>
                        <th>Start Date</th>
                        <th>Duration</th>
                        <th>Progress</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            {{ $task->name }}<i class="ri-eye-line" data-bs-toggle="tooltip" data-bs-html="true"
                                data-bs-title="{{ $task->description }}"></i>
                        </td>
                        <td>
                            <a href="{{route('project.show',['id'=>$task->project_id])}}">{{ $task->project->name }}</a>

                        </td>
                        <td>
                            @foreach ($task->employees as $employer)
                            <span class="badge bg-primary">{{ $employer->full_name }}</span>
                            @endforeach
                        </td>


                        <td>
                            {{ $task->date }}
                        </td>
                        <td>
                            {{ $task->duration }} hours
                        </td>
                        <td>
                            <div class="progress ">
                                <div class="progress-bar" role="progressbar" style="width: {{ $task->progress }}%;"
                                    aria-valuenow="{{ $task->progress }}" aria-valuemin="0" aria-valuemax="100">
                                    {{$task->progress }}%</div>
                            </div>
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
                                data-bs-target="#editTask{{ $task->id }}"><i class="ri-edit-fill"></i></a>

                            <!-- Modal for editing an task -->
                            <div class="modal fade" id="editTask{{ $task->id }}" tabindex="-1"
                                aria-labelledby="editTaskLabel{{ $task->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editTaskLabel{{ $task->id }}">
                                                Edit Task</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form id="editTaskForm{{ $task->id }}"
                                            action="{{ route('task.update', $task->id) }}" method="POST">
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
                                                        value="{{$task->priority}}" name="priority" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea class="form-control" id="description"
                                                        name="description">{{$task->description}}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Progress</label>
                                                    <input type="text" id="range_02" data-plugin="range-slider"
                                                        data-min="0" data- name="progress"
                                                        data-from="{{$task->description}}" />
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
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('assets/vendor/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
<script src="{{asset('assets/js/pages/component.range-slider.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#addTask').on('shown.bs.modal', function () {
            $('.select2').select2({
                dropdownParent: $('#addTask'),
                allowClear: true
            });
        });

    });
</script>
<script>
    function confirmDelete(id) {
      if (confirm('Are you sure you want to delete this Expense?')) {
          document.getElementById('delete-form-' + id).submit();
      }
    }
</script>
@endpush