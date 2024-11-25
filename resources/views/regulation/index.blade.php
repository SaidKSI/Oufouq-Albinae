@extends('layouts.app')
@section('title', 'Regulation')
@section('content')
<x-Breadcrumb title="{{ $type === 'supplier' ? 'Regulation Fournisseur' : 'Regulation Client' }}" />
<div class="row">
  <div class="card">

    <form method="GET" action="{{ route('regulation.index', ['type' => $type]) }}" class="m-3">
      <div class="form-group w-25">
        <label for="filter_id">Filter by {{ $type === 'supplier' ? 'Supplier' : 'Client' }}</label>
        <select name="{{ $type === 'supplier' ? 'supplier_id' : 'client_id' }}" id="filter_id"
          class="form-control w-25 m-2 select2 w-50">
          <option value="">All {{ $type === 'supplier' ? 'Suppliers' : 'Clients' }}</option>
          @foreach($filterEntity as $entity)
          <option value="{{ $entity->id }}" {{ $selectedEntity==$entity->id ? 'selected' : '' }}>
            {{ $type === 'supplier' ? $entity->full_name : $entity->name }}
          </option>
          @endforeach
        </select>
      </div>
    </form>
    <table id="basic-datatable" class="table table-striped dt-responsive w-100">
      <thead>
        <tr>
          <th>N°</th>
          <th>{{ $type === 'supplier' ? 'Supplier' : 'Client' }}</th>
          <th>Project Name</th>
          <th>Payment Method</th>
          <th>Total Price <small>without tax</small></th>
          <th>Total Price <small>with tax</small></th>
          <th>Remaining Amount</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($deliveries as $delivery)
        <tr>
          <td>{{$delivery->number}}</td>
          <td style="font-size: 0.8rem">{{ $type === 'supplier' ? $delivery->supplier->full_name :
            $delivery->project->client->name }}</td>
          <td style="font-size: 0.8rem">{{$delivery->project->name}}</td>
          <td>{{$delivery->payment_method}}</td>
          <td>
            {{$delivery->total_without_tax}}
          </td>
          <td>
            {{$delivery->total_with_tax}}
          </td>
          <td>
            {{number_format($delivery->remaining_amount, 2)}}
          </td>

        </tr>


        @endforeach
      </tbody>
    </table>
    <div class="text-end m-2">
      <a href="{{ route('regulation.print', [
            'type' => $type,
            $type === 'supplier' ? 'supplier_id' : 'client_id' => $selectedEntity
        ]) }}" class="btn btn-primary" target="_blank">
        <i class="ri-printer-line me-1"></i> Imprimer
      </a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    // Initialize Select2 on the select element
    $('#filter_id').select2({
      placeholder: "Select an option",
      allowClear: true
    });

    // Submit the form on change
    $('#filter_id').on('change', function() {
      this.form.submit();
    });
  });
</script>
@endpush