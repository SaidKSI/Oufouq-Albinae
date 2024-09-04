@extends('layouts.app')
@section('title', 'Products')
@section('content')
<x-Breadcrumb title="Products" />
<div class="row">
    <div class="card">
        <div class="col-md-2 mx-3 my-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#addProductModal">Add</button>
            <!-- Add Product Modal -->
            <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addProductForm" action="{{route('product.store')}}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                                <div class="mb-3">
                                    <label for="unit" class="form-label">Unit</label>
                                    <select class="form-select" name="unit" required>
                                        <option value="pieces">pieces</option>
                                        <option value="kg">kg</option>
                                        <option value="liters">liters</option>
                                        <option value="m2">m<sub>2</sub></option>
                                        <option value="m3">m<sub>3</sub></option>
                                    </select>
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
            <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Unit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td><a href="#" class="text-dark">{{$product->name}}</a></td>
                        <td> {{$product->unit}} </td>
                        <td>
                            <a class="text-primary pointer" data-bs-toggle="modal"
                                data-bs-target="#editProductModal{{$product->id}}"><i class="ri-edit-fill"></i></a>
                            <!-- Edit Product Modal -->
                            <div class="modal fade
                                " id="editProductModal{{$product->id}}" tabindex="-1"
                                aria-labelledby="editProductModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form id="editProductForm{{$product->id}}"
                                            action="{{route('product.update', $product->id)}}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body
                                                ">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Product Name</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        value="{{$product->name}}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="unit" class="form-label">Unit</label>
                                                    <select class="form-select" name="unit" required>
                                                        <option value="pieces" @if($product->unit == 'pieces') selected
                                                            @endif>pieces</option>
                                                        <option value="kg" @if($product->unit == 'kg') selected
                                                            @endif>kg</option>
                                                        <option value="liters" @if($product->unit == 'liters') selected
                                                            @endif>liters</option>
                                                        <option value="m2" @if($product->unit == 'm2') selected
                                                            @endif>m<sub>2</sub></option>
                                                        <option value="m3" @if($product->unit == 'm3') selected
                                                            @endif>m<sub>3</sub></option>
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <form id="delete-form-{{$product->id}}"
                                action="{{ route('product.destroy', $product->id) }}" method="POST"
                                style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <a href="#" class="text-danger" onclick="confirmDelete({{$product->id}})"><i
                                class="ri-delete-bin-2-fill"></i></a>
            

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
@endpush