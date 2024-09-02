@extends('layouts.app')

@section('content')
<x-Breadcrumb title="Les Achats" />
<div class="row">
  <div class="card">
    <div class="d-flex card-header justify-content-between align-items-center">
      <h4 class="header-title">Bon de Commande 12</h4>
      <div class="dropdown">
        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="ri-more-2-fill"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end">
          <!-- item-->
          <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addSupplierModal">Add</a>

          <!-- item-->
          <a href="javascript:void(0);" class="dropdown-item">Export</a>
        </div>
      </div>
    </div>
    <!-- Add Supplier Modal -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addSupplierModalLabel">Add Supplier</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="addSupplierForm">
              <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
              </div>
              <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" required>
              </div>
              <div class="mb-3">
                <label for="social_media" class="form-label">Social Media</label>
                <div id="social_media_container">
                  <div class="input-group mb-2">
                    <input type="text" class="form-control" name="social_media_key[]" placeholder="Key">
                    <input type="text" class="form-control" name="social_media_value[]" placeholder="Value">
                  </div>
                </div>
              </div>
                <div class="mb-3 text-center">
                <button type="button" class="btn btn-primary add-social-media">Add</button>
                </div>
              <div class="mb-3">
                <label for="activity" class="form-label">Activity</label>
                <input type="text" class="form-control" id="activity" name="activity" required>
              </div>
              <button type="submit" class="btn btn-primary">Save</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body p-2">
      <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
        <thead>
          <tr>
            <th>Supplier</th>
            <th>Ref</th>
            <th>Articles</th>
            <th>Total Price</th>
            <th>Date de Commande</th>
            <th>Document</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Supplier A</td>
            <td>REF123</td>
            <td><a href="javascript:void(0);" class="article-icon" data-bs-toggle="modal" data-bs-target="#articleModal" data-articles='[{"name":"Article 1","prix_per_unite":"10","unite":"5","total_price":"50","status":"Delivered"},{"name":"Article 2","prix_per_unite":"20","unite":"3","total_price":"60","status":"Not Delivered"}]'><i class="ri-file-list-3-line"></i></a></td>
            <td>110</td>
            
            <td>12-06-2025</td>
            <td>
              <a href="">PDF</a>
            </td>
          </tr>
          <tr>
            <td>Supplier B</td>
            <td>REF456</td>
            <td><a href="javascript:void(0);" class="article-icon" data-bs-toggle="modal" data-bs-target="#articleModal" data-articles='[{"name":"Article 3","prix_per_unite":"15","unite":"4","total_price":"60","status":"Delivered"},{"name":"Article 4","prix_per_unite":"25","unite":"2","total_price":"50","status":"Not Delivered"}]'><i class="ri-file-list-3-line"></i></a></td>
            <td>110</td>
            <td>12-06-2025</td>
            <td><a href="">PDF</a></td>

          </tr>
        </tbody>
      </table>
    </div>
    <div class="modal fade" id="articleModal" tabindex="-1" aria-labelledby="articleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="articleModalLabel">Commande Items</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Prix per Unite</th>
                  <th>Unite</th>
                  <th>Total Price</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="articleModalBody">
                <!-- Article details will be populated here -->
              </tbody>
            </table>
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
    // Handle showing article details in the modal
    $('.article-icon').on('click', function() {
      var articles = $(this).data('articles');
      var modalBody = $('#articleModalBody');
      modalBody.empty();
      articles.forEach(function(article) {
        var row = `
          <tr>
            <td>${article.name}</td>
            <td>${article.prix_per_unite}</td>
            <td>${article.unite}</td>
            <td>${article.total_price}</td>
            <td>${article.status}</td>
          </tr>`;
        modalBody.append(row);
      });
    });

    // Initialize DataTable
    $('#basic-datatable').DataTable();
  });
</script>
@endpush