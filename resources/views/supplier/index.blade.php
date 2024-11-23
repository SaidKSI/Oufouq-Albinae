@extends('layouts.app')
@section('title', 'Suppliers')

@section('content')
<x-Breadcrumb title="Supplier" />
<div class="row">
  <div class="card">
    <div class="d-flex card-header justify-content-between align-items-center">
      <h4 class="header-title">Suppliers {{$supplierCount}}</h4>
      <div class="dropdown">
        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="ri-more-2-fill"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end">
          <a href="javascript:void(0);" class="dropdown-item">Export</a>
        </div>
      </div>
    </div>
    <div class="col-md-2 mx-3 my-2">
      <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">Add</button>
      <!-- Add Supplier Modal -->
      <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addSupplierModalLabel">Add Supplier</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSupplierForm" action="{{route('supplier.store')}}" method="POST">
              @csrf
              <div class="modal-body">
                <div class="mb-3">
                  <label for="full_name" class="form-label">Full Name</label>
                  <input type="text" class="form-control" id="full_name" name="full_name">
                </div>
                <div class="mb-3">
                  <label for="ice" class="form-label">ICE</label>
                  <input type="text" class="form-control" id="ice" name="ice">
                </div>
                <div class="mb-3">
                  <label for="phone" class="form-label">Phone</label>
                  <input type="text" class="form-control" id="phone" name="phone">
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="mb-3">
                  <label for="city" class="form-label">City</label>
                  <input type="text" class="form-control" id="city" name="city">
                </div>
                <div class="mb-3">
                  <label for="address" class="form-label">Address</label>
                  <input type="text" class="form-control" id="address" name="address">
                </div>
                <div class="row">
                  <h3 class="text-center">Social Media</h3>
                  <div class="col">
                    <div class="mb-3">
                      <input type="text" class="form-control" id="facebook" name="facebook" placeholder="Facebook">
                    </div>
                    <div class="mb-3">
                      <input type="text" class="form-control" id="twitter" name="twitter" placeholder="Twitter">
                    </div>
                  </div>
                  <div class="col">
                    <div class="mb-3">
                      <input type="text" class="form-control" id="linkedin" name="linkedin" placeholder="Linkedin">
                    </div>
                    <div class="mb-3">
                      <input type="text" class="form-control" id="instagram" name="instagram" placeholder="Instagram">
                    </div>
                  </div>
                </div>

                <div class="form-floating">
                  <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea"
                    style="height: 100px" name="description"></textarea>
                  <label for="floatingTextarea">Description</label>
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
            <th>Name</th>
            <th>CIE</th>
            <th>Phone</th>
            <th>Email</th>
            <th>City</th>
            <th>Description</th>
            <th>Address</th>
            <th>Total Amount</th>
            <th>Paid Amount</th>
            <th>Remaining Amount</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($suppliers as $supplier)
          <tr>
            <td><a href="{{route('supplier.show', $supplier->id)}}">{{$supplier->full_name}}</a></td>
            <td>{{$supplier->cie}}</td>
            <td>{{$supplier->phone}}</td>
            <td>{{$supplier->email}}</td>
            <td>{{$supplier->city}}</td>
            <td>
              <i class="ri-eye-fill" data-bs-toggle="modal" data-bs-target="#supplierModal{{$supplier->id}}"
                style="cursor: pointer;"></i>

              <!-- Supplier Modal -->
              <div class="modal fade" id="supplierModal{{$supplier->id}}" tabindex="-1"
                aria-labelledby="supplierModalLabel{{$supplier->id}}" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="supplierModalLabel{{$supplier->id}}">{{$supplier->full_name}}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-wrap">
                      {{$supplier->description}}

                      <ul class="social-list list-inline mt-3 mb-0">
                        <li class="list-inline-item">
                          <a href="https://www.facebook.com/{{$supplier->facebook_handle}}" target="_blank"
                            class="social-list-item border-primary text-primary">
                            <i class="ri-facebook-circle-fill"></i>
                          </a>
                        </li>
                        <li class="list-inline-item">
                          <a href="https://www.linkedin.com/in/{{$supplier->linkedin_handle}}" target="_blank"
                            class="social-list-item border-primary text-primary">
                            <i class="ri-linkedin-fill"></i>
                          </a>
                        </li>
                        <li class="list-inline-item">
                          <a href="https://twitter.com/{{$supplier->twitter_handle}}" target="_blank"
                            class="social-list-item border-info text-info">
                            <i class="ri-twitter-fill"></i>
                          </a>
                        </li>
                        <li class="list-inline-item">
                          <a href="https://www.instagram.com/{{$supplier->instagram_handle}}" target="_blank"
                            class="social-list-item border-danger text-danger">
                            <i class="ri-instagram-fill"></i>
                          </a>
                        </li>
                      </ul>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
            </td>
            <td>{{$supplier->address}}</td>
            <td>{{ number_format($supplier->total_delivery_amount, 2) }}</td>
            <td>{{ number_format($supplier->total_paid_amount, 2) }}</td>
            <td>{{ number_format($supplier->remaining_amount, 2) }}</td>
            <td>
              <a href="#" class="text-danger" onclick="confirmDelete({{$supplier->id}})"><i
                  class="ri-delete-bin-2-fill"></i></a>

              <form id="delete-form-{{$supplier->id}}" action="{{ route('supplier.destroy', $supplier->id) }}"
                method="POST" style="display: none;">
                @csrf
                @method('DELETE')
              </form>
              <a href="#" class="text-primary" data-bs-toggle="modal"
                data-bs-target="#editSupplierModal{{$supplier->id}}"><i class="ri-edit-fill"></i></a>

              <!-- Edit Supplier Modal -->
              <div class="modal fade" id="editSupplierModal{{$supplier->id}}" tabindex="-1"
                aria-labelledby="editSupplierModalLabel{{$supplier->id}}" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="editSupplierModalLabel{{$supplier->id}}">Edit Supplier</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editSupplierForm{{$supplier->id}}" action="{{route('supplier.update', $supplier->id)}}"
                      method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-body">
                        <div class="mb-3">
                          <label for="full_name" class="form-label">Full Name</label>
                          <input type="text" class="form-control" id="full_name" name="full_name"
                            value="{{$supplier->full_name}}">
                        </div>
                        <div class="mb-3">
                          <label for="ice" class="form-label">ICE</label>
                          <input type="text" class="form-control" id="ice" name="ice" value="{{$supplier->ice}}">
                        </div>
                        <div class="mb-3">
                          <label for="phone" class="form-label">Phone</label>
                          <input type="text" class="form-control" id="phone" name="phone" value="{{$supplier->phone}}">
                        </div>
                        <div class="mb-3">
                          <label for="email" class="form-label">Email</label>
                          <input type="email" class="form-control" id="email" name="email" value="{{$supplier->email}}">
                        </div>
                        <div class="mb-3">
                          <label for="city" class="form-label">City</label>
                          <input type="text" class="form-control" id="city" name="city" value="{{$supplier->city}}">
                        </div>
                        <div class="mb-3">
                          <label for="address" class="form-label">Address</label>
                          <input type="text" class="form-control" id="address" name="address"
                            value="{{$supplier->address}}">
                        </div>

                        <div class="row">
                          <h3 class="text-center">Social Media</h3>
                          <div class="col">
                            <div class="mb-3">
                              <input type="text" class="form-control" id="facebook" name="facebook"
                                placeholder="Facebook" value="{{$supplier->facebook_handle}}">
                            </div>
                            <div class="mb-3">
                              <input type="text" class="form-control" id="twitter" name="twitter" placeholder="Twitter"
                                value="{{$supplier->twitter_handle}}">
                            </div>
                          </div>
                          <div class="col">
                            <div class="mb-3">
                              <input type="text" class="form-control" id="linkedin" name="linkedin"
                                placeholder="Linkedin" value="{{$supplier->linkedin_handle}}">
                            </div>
                            <div class="mb-3">
                              <input type="text" class="form-control" id="instagram" name="instagram"
                                placeholder="Instagram" value="{{$supplier->instagram_handle}}">
                            </div>
                          </div>
                        </div>

                        <div class="form-floating">
                          <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea"
                            style="height: 100px" name="description">{{$supplier->description}}</textarea>
                          <label for="floatingTextarea">Description</label>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
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
    // Handle adding new social media key-value pairs
    $(document).on('click', '.add-social-media', function() {
      var newSocialMediaRow = `
        <div class="input-group mb-2">
          <input type="text" class="form-control" name="social_media_key[]" placeholder="Key">
          <input type="text" class="form-control" name="social_media_value[]" placeholder="Value">
          <button type="button" class="btn btn-danger remove-social-media">Remove</button>
        </div>`;
      $('#social_media_container').append(newSocialMediaRow);
    });

    // Handle removing social media key-value pairs
    $(document).on('click', '.remove-social-media', function() {
      $(this).closest('.input-group').remove();
    });

    // Handle form submission (you can customize this part to send data to your server)
  });
</script>
@endpush