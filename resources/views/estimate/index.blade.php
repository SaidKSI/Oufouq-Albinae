@extends('layouts.app')
@section('title', 'Project Estimate')
@section('content')
<x-Breadcrumb title="Project Estimate" />
<div class="row">
  <div class="card">
    <div class="card-body p-2">
      <div class="col-md-2 m-1">
        <a href="{{route('project-estimate.create-invoice')}}" class="btn btn-outline-primary">Create Estimate
          Invoice</a>
      </div>
      <div class="col-md-12">
        <table id="basic-datatable" class="table table-striped dt-responsive">
          <thead>
            <tr>
              <th scope="col">Client</th>
              <th scope="col">Project</th>
              <th scope="col">Items</th>
              <th scope="col">Total Price <small>(without tax)</small></th>
              <th scope="col">Tax</th>
              <th scope="col">Total Amount <small>(with tax)</small> </th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($estimates as $estimate)
            @php
            $totalWithoutTax = $estimate->total_price / (1 + $estimate->tax / 100);
            $taxAmount = $estimate->total_price - $totalWithoutTax;
            @endphp
            <tr>
              <td>{{ $estimate->project->client->name }}</td>
              <td>{{ $estimate->project->name }}</td>
              <td>
                <button type="button" class="btn btn-sm btn-info show-items" data-bs-toggle="modal"
                  data-bs-target="#itemsModal_{{ $estimate->id }}">
                  <i class="ri-eye-line"></i>
                </button>
                <div class="modal fade" id="itemsModal_{{ $estimate->id }}" tabindex="-1"
                  aria-labelledby="itemsModalLabel_{{ $estimate->id }}" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="itemsModalLabel_{{ $estimate->id }} ">Estimate Items</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <table class="table">
                          <thead>
                            <tr>
                              <td>#</td>
                              <th>Reference</th>
                              <th>Quantity</th>
                            </tr>
                          </thead>
                          <tbody id="modalItemsContent_{{ $estimate->id }}">
                            @foreach($estimate->items as $item)
                            <tr>
                              <td>{{ $loop->iteration }}</td>
                              <td>{{ $item->reference }}</td>
                              <td>{{ $item->quantity }}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td>{{ number_format($estimate->total_price,2) }}</td>
              <td><span data-bs-toggle="tooltip" data-bs-html="true"
                  data-bs-title="<b>{{number_format($taxAmount,2)}}</b>">{{ $estimate->tax }} %</span></td>
              <td>

                {{ number_format($estimate->total_price + $taxAmount, 2) }}
              </td>
              <td>
                <a href="#" class="text-danger" onclick="confirmDelete({{$estimate->id}})"><i
                    class="ri-delete-bin-2-fill"></i></a>
                <a href="{{ route('estimate.print', $estimate->id) }}" target="_blank" title="Print"><i
                    class="ri-file-fill fs-4"></i></a>
                <form id="delete-form-{{$estimate->id}}" action="{{ route('estimate.destroy', $estimate->id) }}"
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

@endsection


@push('scripts')

<script>
  function confirmDelete(id) {
      if (confirm('Are you sure you want to delete this estimate?')) {
          document.getElementById('delete-form-' + id).submit();
      }
  }
</script>
@endpush