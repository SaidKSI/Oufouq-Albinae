@extends('layouts.app')
@section('title', 'Employers')
@section('content')
<x-Breadcrumb title="Employers" />
<div class="row">
    <div class="card">
        <div class="col-md-2 mx-3 my-2">
            <!-- Button to trigger the modal -->
            <button class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#addEmployerModal">Add</button>

            <!-- Modal for adding an employer -->
            <div class="modal fade" id="addEmployerModal" tabindex="-1" aria-labelledby="addEmployerModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addEmployerModalLabel">Add Employer</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addEmployerForm" action="{{ route('employee.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" required>
                                </div>
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                                <div class="mb-3">
                                    <label for="cine" class="form-label">CINE</label>
                                    <input type="text" class="form-control" id="cine" name="cine" required>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" required>
                                </div>
                                <div class="form-check">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="cnss">
                                        <label class="form-check-label" for="cnss" name="cnss">CNSS</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="profession_id" class="form-label">Profession</label>
                                    <select class="form-select" id="profession_id" name="profession_id" required>
                                        <option disabled selected>Select a Profession</option>
                                        @foreach($professions as $profession)
                                        <option value="{{ $profession->id }}">{{ $profession->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="wage_per_hr" class="form-label">Wage per Hour</label>
                                    <input type="number" step="0.01" class="form-control" id="wage_per_hr"
                                        name="wage_per_hr" required>
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
                        <th>Full Name</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Profession</th>
                        <th>CNSS</th>
                        <th>Wage Per Hour</th>
                        <th>Join At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employers as $employer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $employer->full_name }}</td>
                        <td>{{ $employer->phone }}</td>
                        <td>{{ $employer->city }}</td>
                        <td>{{ $employer->address }}</td>
                        <td>{{ $employer->profession->name }}</td>
                        <td>
                            @if($employer->cnss)
                            <i class="bi bi-check text-success fs-3"></i>
                            @else
                            <i class="bi bi-x text-danger fs-3"></i>
                            @endif
                        </td>
                        <td>
                            {{$employer->created_at->format('d/m/Y')}}
                        </td>
                        <td>{{ $employer->wage_per_hr }}</td>
                        <td class="nowrap">
                         
                            <a href="#" class="text-danger" onclick="confirmDelete({{$employer->id}})"><i
                                class="ri-delete-bin-2-fill"></i></a>
              
                            <form id="delete-form-{{$employer->id}}"
                                action="{{ route('employee.destroy', $employer->id) }}" method="POST"
                                style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <a href="#" class="text-primary"data-bs-toggle="modal"
                            data-bs-target="#editEmployerModal{{ $employer->id }}"><i class="ri-edit-fill"></i></a>

                            <!-- Modal for editing an employer -->
                            <div class="modal fade
                                " id="editEmployerModal{{ $employer->id }}" tabindex="-1"
                                aria-labelledby="editEmployerModalLabel{{ $employer->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editEmployerModalLabel{{ $employer->id }}">
                                                Edit Employer</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form id="editEmployerForm{{ $employer->id }}"
                                            action="{{ route('employee.update', $employer->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body
                                                ">
                                                <div class="mb-3">
                                                    <label for="full_name" class="form-label">Full Name</label>
                                                    <input type="text" class="form-control" id="full_name"
                                                        name="full_name" value="{{ $employer->full_name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="phone" class="form-label">Phone</label>
                                                    <input type="text" class="form-control" id="phone" name="phone"
                                                        value="{{ $employer->phone }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="city" class="form-label">City</label>
                                                    <input type="text" class="form-control" id="city" name="city"
                                                        value="{{ $employer->city }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Address</label>
                                                    <input type="text" class="form-control" id="address" name="address"
                                                        value="{{ $employer->address }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="profession_id" class="form-label
                                                        ">Profession</label>
                                                    <select class="form-select" id="profession_id" name="profession_id"
                                                        required>
                                                        <option disabled selected>Select a Profession</option>
                                                        @foreach($professions as $profession)
                                                        <option value="{{ $profession->id }}" {{$employer->profession_id == $profession->id ? 'selected' : ''}} >{{ $profession->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                <div class="form-check">
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" id="cnss" {{$employer->cnss ? 'checked' : ''}}>
                                                        <label class="form-check-label" for="cnss" name="cnss" >CNSS</label>
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="mb-3">
                                                    <label for="wage_per_hr" class="form-label
                                                        ">Wage per Hour</label>
                                                    <input type="number" step="0.01" class="form-control"
                                                        id="wage_per_hr" name="wage_per_hr"
                                                        value="{{ $employer->wage_per_hr }}" required>
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
      if (confirm('Are you sure you want to delete this supplier?')) {
          document.getElementById('delete-form-' + id).submit();
      }
    }
</script>
<script>
    $(document).ready(function() {
        $('#profession_id').select2({
            placeholder: "Select a Profession",
            allowClear: true,
            dropdownParent: $('#addEmployerModal'),
            containerCss: { zIndex: 1050 }
        });
    });
</script>
@endpush