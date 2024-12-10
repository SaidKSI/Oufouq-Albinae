@extends('layouts.app')
@section('title', 'Clients')
@section('content')
<x-Breadcrumb title="Clients" />
<div class="row">
    <div class="card">
        <div class="d-flex card-header justify-content-between align-items-center">
            <h4 class="header-title">Clients {{$clientCount}}</h4>
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
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addClientModal">Add</button>
            <!-- Add Client Modal -->
            <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addClientModalLabel">Add Client</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addClientForm" action="{{route('client.store')}}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone">
                                </div>
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city">
                                </div>
                                <div class="mb-3">
                                    <label for="ice" class="form-label">ICE</label>
                                    <input type="text" class="form-control" id="ice" name="ice">
                                </div>
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <select class="form-select" id="type" name="type">
                                        <option value="company">Company</option>
                                        <option value="person">Person</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address">
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
            <table id="basic-datatable" class="table table-striped dt-responsive w-100">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>CIE</th>
                        <th>Type</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                    <tr>
                        <td><a href="#" class="text-dark">{{$client->name}}</a></td>
                        <td>{{$client->ice}}</td>
                        <td>{{$client->type}}</td>
                        <td>{{$client->phone}}</td>
                        <td>{{$client->email}}</td>
                        <td>{{$client->city}}</td>

                        <td>
                            {{$client->address}}
                        </td>
                        <td>
                            {{$client->created_at}}
                        </td>
                        <td>

                            <a href="#" class="text-danger" onclick="confirmDelete({{$client->id}})"><i
                                    class="ri-delete-bin-2-fill"></i></a>

                            <form id="delete-form-{{$client->id}}" action="{{ route('client.destroy', $client->id) }}"
                                method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <a href="#" class="text-primary" data-bs-toggle="modal"
                                data-bs-target="#editClientModal{{$client->id}}"><i class="ri-edit-fill"></i></a>

                            <!-- Edit Client Modal -->
                            <div class="modal fade" id="editClientModal{{$client->id}}" tabindex="-1"
                                aria-labelledby="editClientModalLabel{{$client->id}}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editClientModalLabel{{$client->id}}">Edit
                                                Client</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form id="editClientForm{{$client->id}}"
                                            action="{{route('client.update', $client->id)}}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Full Name</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        value="{{$client->name}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="ice" class="form-label">ICE</label>
                                                    <input type="text" class="form-control" id="ice" name="ice"
                                                        value="{{$client->ice}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="type" class="form-label">Type</label>
                                                    <select class="form-select" id="type" name="type">
                                                        <option value="company" @if($client->type == 'company') selected
                                                            @endif>Company</option>
                                                        <option value="person" @if($client->type == 'person') selected
                                                            @endif>Person</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="phone" class="form-label">Phone</label>
                                                    <input type="text" class="form-control" id="phone" name="phone"
                                                        value="{{$client->phone}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email"
                                                        value="{{$client->email}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="city" class="form-label">City</label>
                                                    <input type="text" class="form-control" id="city" name="city"
                                                        value="{{$client->city}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Address</label>
                                                    <input type="text" class="form-control" id="address" name="address"
                                                        value="{{$client->address}}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
                @endforeach
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
@endpush