@extends('layouts.app')
@section('title', 'Project Estimate')
@section('content')
<x-Breadcrumb title="Project Estimate" />
<div class="row">
  <div class="card">
    <div class="card-body p-2">
      <div class="col-md-2 m-1">
        {{-- <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addestimateModal">Add</button>
        <!-- Add estimate Modal -->
        <div class="modal fade" id="addestimateModal" tabindex="-1" aria-labelledby="addestimateModalLabel"
          aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addestimateModalLabel">Add Project Estimate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form id="addestimateForm" action="{{route('estimate.store')}}" method="POST">
                @csrf
                <div class="modal-body">
                  <div class="mb-3">
                    <label for="client" class="form-label">Client</label>
                    <select class="form-select" id="client_id" name="client_id">
                      <option disabled selected>Select a Client</option>
                      @foreach($clients as $client)
                      <option value="{{ $client->id }}">{{ $client->name }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="mb-3">
                    <label for="project" class="form-label">Project</label>
                    <select class="form-select" id="project_id" name="project_id" disabled>
                      <option disabled selected>Select a Project</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label for="reference" class="form-label">Reference</label>
                    <input type="text" class="form-control" id="reference" name="reference">
                  </div>
                  <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity">
                  </div>
                  <div class="mb-3">
                    <label for="total_price" class="form-label">Total Price</label>
                    <input type="number" class="form-control" id="total_price" name="total_price">
                  </div>
                  <div class="mb-3">
                    <label for="tax" class="form-label">Tax <small>(in %)</small></label>
                    <input type="text" class="form-control" id="tax" name="tax">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div> --}}

        <a href="{{route('project-estimate.create-invoice')}}" class="btn btn-outline-primary">Create Estimate Invoice</a>

      </div>
      <div class="col-md-12">
        <table id="basic-datatable" class="table table-striped dt-responsive">
          <thead>
            <tr>
              <th scope="col">Client</th>
              <th scope="col">Project</th>
              <th scope="col">Reference</th>
              <th scope="col">Quantity</th>
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
              <td>{{ $estimate->reference }}</td>
              <td>{{ $estimate->quantity }}</td>
              <td>{{ number_format($estimate->total_price,2) }}</td>
              <td><span data-bs-toggle="tooltip" data-bs-html="true"
                  data-bs-title="<b>{{number_format($taxAmount,2)}}</b>">{{ $estimate->tax }} %</span></td>
              <td>

                {{ number_format($estimate->total_price + $taxAmount, 2) }}
              </td>
              <td>
                <a href="{{route('project-estimate.invoice',['id'=>$estimate->id])}}" class="text-primary">facture</a>
                <a href="#" onclick="printInvoice({{ $estimate->id }})"><i class="ri-file-list-fill"></i></a>


                <a href="" class="text-primary" data-bs-toggle="modal"
                  data-bs-target="#estimateModal{{$estimate->id}}"><i class="ri-edit-fill"></i></a>

                <!-- Add/Edit Estimate Modal -->
                <div class="modal fade" id="estimateModal{{$estimate->id}}" tabindex="-1"
                  aria-labelledby="estimateModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="estimateModalLabel">Add Project Estimate</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form id="estimateForm" action="{{ route('estimate.update',['id'=>$estimate->id]) }}"
                        method="POST">
                        @method('PUT')
                        @csrf
                        <div class="modal-body">
                          <div class="mb-3">
                            <label for="client" class="form-label">Client</label>
                            <select class="form-select" id="client_id_edit" name="client_id">
                              <option disabled selected>Select a Client</option>
                              @foreach($clients as $client)
                              <option value="{{ $client->id }}">{{ $client->name }}</option>
                              @endforeach
                            </select>
                          </div>

                          <div class="mb-3">
                            <label for="project" class="form-label">Project</label>
                            <select class="form-select" id="project_id_edit" name="project_id" disabled>
                              <option disabled selected>Select a Project</option>
                            </select>
                          </div>

                          <div class="mb-3">
                            <label for="reference" class="form-label">Reference</label>
                            <input type="text" class="form-control" id="reference" name="reference"
                              value="{{$estimate->reference}}">
                          </div>
                          <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity"
                              value="{{$estimate->quantity}}">
                          </div>
                          <div class="mb-3">
                            <label for="total_price" class="form-label">Total Price</label>
                            <input type="number" class="form-control" id="total_price" name="total_price"
                              value="{{$estimate->total_price}}">
                          </div>
                          <div class="mb-3">
                            <label for="tax" class="form-label">Tax <small>(in %)</small></label>
                            <input type="text" class="form-control" id="tax" name="tax" value="{{$estimate->tax}}">
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <a href="#" class="text-danger" onclick="confirmDelete({{$estimate->id}})"><i
                    class="ri-delete-bin-2-fill"></i></a>

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
<!-- Hidden iframe for printing -->
<iframe id="invoiceFrame" style="display:none;"></iframe>
@endsection


@push('scripts')
<script>
  function printInvoice(estimateId) {
        var iframe = document.getElementById('invoiceFrame');
        iframe.style.display = 'block';
        iframe.src = '/dashboard/projects/' + estimateId + '/invoice';
        iframe.onload = function() {
            iframe.contentWindow.print();
            iframe.style.display = 'none';
        };
    }
</script>
<script>
  function confirmDelete(id) {
      if (confirm('Are you sure you want to delete this estimate?')) {
          document.getElementById('delete-form-' + id).submit();
      }
  }
</script>
<script>
  $(document).ready(function() {
      $('#client_id').change(function() {
          var clientId = $(this).val();
          if (clientId) {
              $.ajax({
                  url: '/dashboard/client/' + clientId + '/projects',
                  type: 'GET',
                  dataType: 'json',
                  success: function(data) {
                      $('#project_id').empty().append('<option disabled selected>Select a Project</option>');
                      $.each(data, function(key, project) {
                          $('#project_id').append('<option value="' + project.id + '">' + project.name + '</option>');
                      });
                      $('#project_id').prop('disabled', false);
                  },
                  error: function() {
                      $('#project_id').empty().append('<option disabled selected>Select a Project</option>');
                      $('#project_id').prop('disabled', true);
                  }
              });
          } else {
              $('#project_id').empty().append('<option disabled selected>Select a Project</option>');
              $('#project_id').prop('disabled', true);
          }
      });
  });
  $(document).ready(function() {
      $('#client_id_edit').change(function() {
          var clientId = $(this).val();
          if (clientId) {
              $.ajax({
                  url: '/dashboard/client/' + clientId + '/projects',
                  type: 'GET',
                  dataType: 'json',
                  success: function(data) {
                      $('#project_id_edit').empty().append('<option disabled selected>Select a Project</option>');
                      $.each(data, function(key, project) {
                          $('#project_id_edit').append('<option value="' + project.id + '">' + project.name + '</option>');
                      });
                      $('#project_id_edit').prop('disabled', false);
                  },
                  error: function() {
                      $('#project_id_edit').empty().append('<option disabled selected>Select a Project</option>');
                      $('#project_id_edit').prop('disabled', true);
                  }
              });
          } else {
              $('#project_id_edit').empty().append('<option disabled selected>Select a Project</option>');
              $('#project_id_edit').prop('disabled', true);
          }
      });
  });
</script>
@endpush