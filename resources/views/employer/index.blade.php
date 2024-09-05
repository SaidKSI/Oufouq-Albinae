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
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <input type="text" class="form-control" id="type" name="type" required>
                                </div>
                                <div class="mb-3">
                                    <label for="wage" class="form-label">Wage</label>
                                    <input type="number" step="0.01" class="form-control" id="wage" name="wage"
                                        required>
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
                                    <label for="cnss" class="form-label">CNSS</label>
                                    <input type="text" class="form-control" id="cnss" name="cnss" required>
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


    </div>
</div>
@endsection

@push('scripts')
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