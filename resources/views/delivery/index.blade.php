@extends('layouts.app')
@section('title', 'Delivery')
@section('content')
<x-Breadcrumb title="Delivery" />
<div class="row">
  <div class="card">
    <div class="col-md-2 mx-3 my-1">
      <a href="{{route('delivery.invoice')}}" class="btn btn-outline-primary">Create invoice</a>
    </div>
    <form method="GET" action="{{ route('delivery') }}">
      <div class="form-group">
        <label for="supplier_id">Filter by Supplier</label>
        <select name="supplier_id" id="supplier_id" class="form-control w-25 m-2" onchange="this.form.submit()">
          <option value="">All Suppliers</option>
          @foreach($suppliers as $supplier)
          <option value="{{ $supplier->id }}" {{ $selectedSupplier==$supplier->id ? 'selected' : '' }}>
            {{ $supplier->full_name }}
          </option>
          @endforeach
        </select>
      </div>
    </form>
    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
      <thead>
        <tr>
          <th>N°</th>
          <th>Supplier</th>
          <th>Project Name</th>
          <th>Product</th>
          <th>Total Price <small>without tax</small></th>
          <th>Total Price <small>with tax</small></th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($deliveries as $delivery)
        <tr>
          <td>{{$delivery->number}}</td>
          <td>{{$delivery->supplier->full_name}}</td>
          <td><a href="{{route('project.show',['id'=>$delivery->project_id])}}">{{$delivery->project->name}}
            </a> </td>
          <td>
            <i class="ri-file-list-3-line" data-bs-toggle="modal" data-bs-target="#deliveryDetails{{$delivery->id}}"
              style="cursor: pointer;"></i>
            <div class="modal fade" id="deliveryDetails{{$delivery->id}}" tabindex="-1"
              aria-labelledby="deliveryDetailsLabel{{$delivery->id}}" aria-hidden="true">
              <div class="modal-dialog modal-full-width">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="deliveryDetailsLabel{{$delivery->id}}">delivery Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Ref</th>
                          <th>Product Name</th>
                          <th>Category</th>
                          <th>Prix per Unite</th>
                          <th>quantity</th>
                          <th>Total Price</th>
                        </tr>
                      </thead>
                      <tbody id="articleModalBody">
                        @foreach ($delivery->items as $item)
                        <tr>
                          <td>{{$item->ref}}</td>
                          <td>{{$item->name}}</td>
                          <td>{{$item->category}}</td>
                          <td>{{$item->prix_unite}}</td>
                          <td>{{$item->qte}}</td>
                          <td>{{$item->total_price_unite}}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </td>
          <td>
            {{$delivery->total_without_tax}}
          </td>
          <td>
            {{$delivery->total_with_tax}}
          </td>
          <td>
            <a href="{{route('delivery.show',['id'=>$delivery->id])}}"><i class="ri-eye-fill"></i></a>

            <a href="#" onclick="printInvoice({{ $delivery->id }})"><i class="ri-file-list-fill"></i></a>

            <a href="#" class="text-danger" onclick="confirmDelete({{$delivery->id}})"><i
                class="ri-delete-bin-2-fill"></i></a>

            <form id="delete-form-{{$delivery->id}}" action="{{ route('delivery.destroy', $delivery->id) }}" method="POST"
              style="display: none;">
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
<iframe id="invoiceFrame" style="display:none;"></iframe>

@endsection


@push('scripts')
<script>
  function confirmDelete(id) {
      if (confirm('Are you sure you want to delete this delivary?')) {
          document.getElementById('delete-form-' + id).submit();
      }
  }
</script>
<script>
     function printInvoice(deliveryId) {
        var iframe = document.getElementById('invoiceFrame');
        iframe.style.display = 'block';
        iframe.src = '/dashboard/order/delivery/print/' + deliveryId;
        iframe.onload = function() {
            iframe.contentWindow.print();
            iframe.style.display = 'none';
        };
    }
</script>
@endpush