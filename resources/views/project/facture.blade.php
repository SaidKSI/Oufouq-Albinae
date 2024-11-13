@extends('layouts.app')
@section('title', 'Create Invoice')
@section('content')
<x-Breadcrumb title="Create Invoice" />
<div class="row bg-secondary">
  <div class="card">
    <form action="{{ route('project.store_invoice') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <div class="container shadow"
          style="margin-top: 46px;background: url(&quot;{{asset('assets/invoice_asset/img/Oufoq%20albinae%20BIG.png')}}&quot;) center / cover no-repeat;border-radius: 12px;min-height: 1000px;">
          <div class="vstack">
            <div class="row d-flex d-xl-flex align-items-center align-items-xl-center">
              <div class="col"><img src="{{asset('assets/invoice_asset/img/logo%20big.png')}}"
                  style="width: 150px;background: rgba(255,255,255,0);"></div>
              <div class="col">
                <h4 class="text-capitalize text-center">Facture N°# <input type="text" name="number" id="number"
                    value=""> </h4>
              </div>
              <div class="col text-center"><span class="fw-bold" style="margin-right: 22px;">Salé</span><span>Le <input
                    class="border-0 focus-ring form-control-sm" type="date" style="width: 120px;" name="date"
                    value="{{ date('Y-m-d') }}"></span></div>
            </div>
          </div>
          <hr>
          <div>
            <table class="table table-sm table-borderless">
              <thead>
                <tr class="text-uppercase text-center">
                  <th
                    style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-bottom-style: none;width: 155px;">
                    Estimate</th>
                  <th
                    style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-bottom-style: none;width: 175px;">
                    Payment Method</th>
                  <th
                    style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-bottom-style: none;width: 175px;">
                    Transaction ID</th>
                </tr>
              </thead>
              <tbody>
                <tr class="text-uppercase text-center">
                  <td style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0) ;border-top-style: none;">
                    <div class="input-group">
                      <select class="bg-transparent border-0 focus-ring form-select select2" id="estimate"
                        name="estimate">
                        <option disabled selected>Choisir un estimate</option>
                        @foreach ($estimates as $estimate)
                        <option value="{{ $estimate->id }}">{{ $estimate->project->name }} - {{ $estimate->number }}
                        </option>
                        @endforeach
                      </select>
                    </div>
                  </td>
                  <td style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0) ;border-top-style: none;">
                    <div class="input-group">
                      <select class="bg-transparent border-0 focus-ring form-select" id="payment_method"
                        name="payment_method">
                        <option disabled selected>Mode Reglement</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cheque">Chèque</option>
                        <option value="credit">Credit</option>
                        <option value="cash">Cash</option>
                        <option value="traita">Traita</option>
                      </select>
                    </div>
                  </td>
                  <td style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0) ;border-top-style: none;">
                    <div class="input-group">
                      <input class="form-control" type="text" id="transaction_id" name="transaction_id">
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="text-center" style="margin-bottom: 20px;">
            <table class="table table-sm table-borderless">
              <thead>
                <tr class="text-uppercase">
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">Référence
                  </th>
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                    Désignation</th>
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">Qté</th>
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">Prix
                    unitaire</th>
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">catégorie
                  </th>
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">montant
                  </th>
                </tr>
              </thead>
              <tbody id="invoice-table-body">

              </tbody>
            </table>
          </div>
          <div class="text-center" style="margin-bottom: 20px;">
            <button id="add-row"
              class="btn btn-outline-success btn-sm fw-bold border rounded-pill border-1 border-success" type="button"
              style="width: 30%;padding: 0;margin-bottom: 9px;margin-top: 8px;">
              <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16"
                class="bi bi-plus-lg">
                <path fill-rule="evenodd"
                  d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2">
                </path>
              </svg>&nbsp;Nouvelle ligne
            </button>
          </div>
          <div class="text-center" style="margin-bottom: 20px;">
            <table class="table table-sm table-borderless">
              <tr>
                <th class="text-capitalize border-2 border-dark" style="background: rgba(255,255,255,0);" colspan="3"
                  rowspan="2">Arreté La présente
                  facture à la somme de :<br>#... <span id="numberToWord"></span> ...#
                </th>
                <th class="text-uppercase border-2 border-dark" style="background: rgba(255,255,255,0);">total ht</th>
                <th class="text-uppercase border-2 border-dark" style="background: rgba(255,255,255,0);">tva (20%)</th>
                <th class="text-uppercase border-2 border-dark" style="background: rgba(255,255,255,0);">total</th>
              </tr>

              <tr>
                <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                  <input type="number" name="total_without_tax" id="total_without_tax" class="form-control" step="0.01"
                    placeholder="0.00">
                </td>
                <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                  <div class="input-group">
                    <input class="form-control" type="number" value="0.00" id="tax" name="tax" min="0" max="100" step="0.01">
                  </div>
                </td>
                <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                  <input type="text" name="total_with_tax" id="total_with_tax" class="form-control" readonly>
                </td>
              </tr>
            </table>
          </div>
          <p class="fw-bold">Pièces Jointes :</p>
          <button id="upload-button"
            class="btn btn-outline-success btn-sm text-nowrap fw-bold border rounded-pill border-1 border-success"
            type="button" style="width: 30%;padding: 0px;margin-bottom: 15px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16"
              class="bi bi-cloud-upload-fill" disabled>
              <path fill-rule="evenodd"
                d="M8 0a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 4.095 0 5.555 0 7.318 0 9.366 1.708 11 3.781 11H7.5V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V11h4.188C14.502 11 16 9.57 16 7.773c0-1.636-1.242-2.969-2.834-3.194C12.923 1.999 10.69 0 8 0m-.5 14.5V11h1v3.5a.5.5 0 0 1-1 0">
              </path>
            </svg>&nbsp;Ajouter des Pièces Jointes
          </button>
          <span id="doc-name">

          </span>
          <input type="file" name="doc" id="doc" style="display: none" multiple accept="image/*">
          <div id="file-list"></div>
          <div class="text-center" style="margin-bottom: 20px;">
            <h3>Remarques</h3>
            <div class="input-group"><textarea class="shadow-sm form-control" rows="12" style="height: auto"
                style="margin-bottom: 30px;border-radius: 10px;" name="note"></textarea></div>
          </div>
          <div class="text-center" style="margin-bottom: 20px;">
            <button class="btn btn-outline-success btn-sm fw-bold border rounded-pill border-1 border-success"
              type="submit" style="width: 30%;padding: 0px;margin-bottom: 15px;">Enregistrer</button>
          </div>
          <div class="vstack">
            <div style="width: 100%;height: 3px;background: #ed961c;border-radius: 26px;margin-bottom: 5px;"></div>
            <p class="fw-bold text-center" style="font-size: 13px;">Adresse : N°97 Rue Assila Laayayda Salé / IF :
              3341831 / ICE :&nbsp; 000095738000027/ RC : 16137 CNSS : 8712863&nbsp;<br>Patente : 28565292 / Capitale :
              100 000,00 Gsm : 06 98 46 33 60 - 06 61 78 99 70<br>E-mail :&nbsp;contact@oufoqalbinae.com</p>
            <p class="text-capitalize text-center text-muted">Merci de Votre Confiance</p>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('styles')
<style>
  input[type="text"],
  input[type="number"] {
    border: none;
    box-shadow: none;
    outline: none;
    background: transparent;
  }

  textarea {
    border: none;
    box-shadow: none;
    outline: none;
    background: transparent;
    height: 10px;
  }

  input[readonly] {
    background-color: rgba(255, 255, 255, 0.1) !important;
    cursor: not-allowed;
  }

  .file-item {
    margin: 5px 0;
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 4px;
  }
</style>
<link rel="stylesheet"
  href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&amp;display=swap">
@endpush

@push('scripts')
<script>
  $(document).ready(function() {
    $('.select2').select2();

    // Generate random invoice number
    $('#number').val(Math.floor(Math.random() * 900000) + 100000);

    // Handle estimate selection
    $('#estimate').on('change', function() {
        const estimateId = $(this).val();
        if (estimateId) {
            fetch(`/dashboard/estimates/${estimateId}/details`)
                .then(response => response.json())
                .then(data => {
                    // Update form fields with estimate data
                    updateFormWithEstimateData(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error fetching estimate details');
                });
        }
    });

    function updateFormWithEstimateData(data) {
        // Update totals and tax
        $('#total_without_tax').val(data.total_without_tax).prop('readonly', true);
        $('#tax').val(data.tax).prop('readonly', true);
        
        // Calculate and set total with tax
        const totalWithoutTax = parseFloat(data.total_with_tax) || 0;
        const tax = parseFloat(data.tax) || 0;
        const totalWithTax =  totalWithoutTax;
        const note = data.note;
        const doc = data.doc;
        $('#total_with_tax').val(totalWithTax.toFixed(2));
        $('textarea[name="note"]').val(note);
        $('#doc-name').val(doc);
        // Update items table
        const tableBody = $('#invoice-table-body');
        tableBody.empty();

        data.items.forEach(item => {
            const row = `
                <tr>
                    <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                        <input class="form-control" type="text" name="ref[]" value="${item.ref}" readonly>
                    </td>
                     <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                      <textarea class="form-control" rows="3" name="name[]" >${item.name}</textarea>
                    </td>
                    <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                        <input class="form-control quantity" type="number" name="qte[]" value="${item.qte}" readonly>
                    </td>
                    <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                        <input class="form-control unit-price" type="number" name="prix_unite[]" value="${item.prix_unite}">
                    </td>
                    <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                        <textarea class="form-control" rows="3" name="category[]">${item.category}</textarea>
                    </td>
                    <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                      <input class="form-control total-price" type="number" name="total_price_unite[]" value="${item.total_price_unite}" readonly>
                     </td>
                </tr>
            `;
            tableBody.append(row);
        });

        // Update number to word
        updateNumberToWord(totalWithTax);

        // Update documents display
        const docContainer = $('#doc-name');
        docContainer.empty(); // Clear existing content

        if (data.documents && data.documents.length > 0) {
            data.documents.forEach(doc => {
                const docLink = `
                    <div class="doc-item mb-2">
                        <a href="${doc.url}" target="_blank" class="text-primary">
                            <i class="ri-file-text-line me-1"></i>
                            ${doc.name}
                        </a>
                    </div>
                `;
                docContainer.append(docLink);
            });
        } else {
            docContainer.html('<span class="text-muted">No documents attached</span>');
        }
    }

    function updateNumberToWord(amount) {
        fetch(`/dashboard/order/delivery/${amount}/to-number`)
            .then(response => response.json())
            .then(data => {
                $('#numberToWord').text(data);
            })
            .catch(error => console.error('Error:', error));
    }

    // Keep your existing file upload code
    const uploadButton = document.getElementById('upload-button');
    const fileInput = document.getElementById('doc');
    const fileList = document.getElementById('file-list');
    uploadButton.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', () => {
        fileList.innerHTML = '';
        Array.from(fileInput.files).forEach(file => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            fileItem.textContent = file.name;
            fileList.appendChild(fileItem);
        });
    });
});
</script>
@endpush