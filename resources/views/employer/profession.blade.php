@extends('layouts.app')
@section('title', 'Employees Profession')
@section('content')
<x-Breadcrumb title="Employees Profession" />
<div class="row">
    <div class="card">
        <div class="col-md-2 mx-3 my-2">
            <!-- Button to trigger the modal -->
            <button class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#addProfessionModal">Add</button>

            <!-- Modal for adding an profession -->
            <div class="modal fade" id="addProfessionModal" tabindex="-1" aria-labelledby="addProfessionModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProfessionModalLabel">Add Profession</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addProfessionForm" action="{{ route('profession.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Profession Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
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
        <div class="card-body p-2">
            <table id="basic-datatable" class="table table-striped dt-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Employees Count</th>
                        <th>Averge Wage</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($professions as $profession)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $profession->name }}</td>
                        <td>{{ $profession->employees->count() }}
                            <button class="btn btn-link" data-bs-toggle="modal"
                                data-bs-target="#employeesModal{{ $profession->id }}">
                                <i class="bi bi-person"></i>
                            </button>
                            <!-- Modal for displaying employees -->
                            <div class="modal fade" id="employeesModal{{ $profession->id }}" tabindex="-1"
                                aria-labelledby="employeesModalLabel{{ $profession->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="employeesModalLabel{{ $profession->id }}">
                                                Employees with {{ $profession->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-striped dt-responsive w-100 nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Full Name</th>
                                                        <th>Phone</th>
                                                        <th>City</th>
                                                        <th>Address</th>
                                                        <th>CNSS</th>
                                                        <th>Wage Per Hour</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($profession->employees as $employer)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $employer->full_name }}</td>
                                                        <td>{{ $employer->phone }}</td>
                                                        <td>{{ $employer->city }}</td>
                                                        <td>{{ $employer->address }}</td>
                                                        <td>
                                                            @if($employer->cnss)
                                                            <i class="bi bi-check text-success fs-3"></i>
                                                            @else
                                                            <i class="bi bi-x text-danger fs-3"></i>
                                                            @endif
                                                        </td>
                                                        <td>{{ $employer->wage_per_hr }}</td>
                                                        
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $profession->employees->avg('wage_per_hr') ?? 0}}</td>
                        <td>
                            <a href="#" class="text-danger" onclick="confirmDelete({{$profession->id}})"><i
                                    class="ri-delete-bin-2-fill"></i></a>

                            <form id="delete-form-{{$profession->id}}"
                                action="{{ route('profession.destroy', $profession->id) }}" method="POST"
                                style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <a href="#" class="text-primary" data-bs-toggle="modal"
                                data-bs-target="#editProfessionModal{{$profession->id}}"><i
                                    class="ri-edit-fill"></i></a>

                            <!-- Modal for updating the profession -->
                            <div class="modal fade" id="editProfessionModal{{$profession->id}}" tabindex="-1"
                                aria-labelledby="editProfessionModalLabel{{$profession->id}}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editProfessionModalLabel{{$profession->id}}">
                                                Update Profession</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form id="editProfessionForm{{$profession->id}}"
                                            action="{{ route('profession.update', $profession->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="edit_name{{$profession->id}}"
                                                        class="form-label">Profession Name</label>
                                                    <input type="text" class="form-control"
                                                        id="edit_name{{$profession->id}}" name="name"
                                                        value="{{ $profession->name }}" required>
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
<script>
    function confirmDelete(id) {
      if (confirm('Are you sure you want to delete this profession?')) {
          document.getElementById('delete-form-' + id).submit();
      }
    }
</script>
@endpush