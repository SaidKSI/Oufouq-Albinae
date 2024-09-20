@extends('layouts.app')
@section('title', 'Create Invoice')
@section('content')
<x-Breadcrumb title="Create Invoice" />
<div class="row bg-secondary">
  <div class="card">
    <form action="{{route('delivery.store')}}" method="POST">
      @csrf
      <div class="card-body">
        <div class="container shadow"
          style="margin-top: 46px;background: url(&quot;{{asset('assets/invoice_asset/img/Oufoq%20albinae%20BIG.png')}}&quot;) center / cover no-repeat;border-radius: 12px;min-height: 1000px;">
          <div class="vstack">
            <div class="row d-flex d-xl-flex align-items-center align-items-xl-center">
              <div class="col"><img src="{{asset('assets/invoice_asset/img/logo%20big.png')}}"
                  style="width: 150px;background: rgba(255,255,255,0);"></div>
              <div class="col">
                <h4 class="text-capitalize text-center">bon de livraison N°# <input
                    type="text" name="number" id="number" readonly> </h4>
              </div>
              <div class="col text-center"><span class="fw-bold" style="margin-right: 22px;">Salé</span><span>Le <input
                    class="border-0 focus-ring form-control-sm" type="date" style="width: 120px;" name="date"></span>
              </div>
            </div>
          </div>
          <hr>
          <div>
            <table class="table table-sm table-borderless">
              <thead>
                <tr class="text-uppercase text-center">
                  <th
                    style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-bottom-style: none;width: 155px;">
                    Supplier</th>
                  <th
                    style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-bottom-style: none;width: 155px;">
                    N° Client</th>
                  <th
                    style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-bottom-style: none;width: 175px;">
                    Mode Reglement</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr class="text-uppercase text-center">
                  <td style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0) ;border-top-style: none;">
                    <div class="input-group">
                      <select class="bg-transparent border-0 focus-ring form-select" id="supplier_id" name="supplier_id">
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->full_name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="input-group"></div>
                  </td>
                  <td style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0) ;border-top-style: none;">
                    <div class="input-group">
                      <select class="bg-transparent border-0 focus-ring form-select" id="client_id" name="client_id">
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="input-group"></div>
                  </td>
                  <td style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0) ;border-top-style: none;">
                    <div class="input-group">
                      <select class="bg-transparent border-0 focus-ring form-select" id="project_id" name="project_id"
                        disabled>
                        <option disabled selected>Select a Project</option>
                      </select>
                    </div>
                  </td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="text-center" style="margin-bottom: 20px;">
            <table class="table table-sm table-borderless">
              <thead>
                <tr class="text-uppercase">
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">Référence</th>
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">Désignation</th>
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">Qté</th>
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">Prix unitaire</th>
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">catégorie</th>
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">montant</th>
                </tr>
              </thead>
              <tbody id="invoice-table-body">

              </tbody>
            </table>
            <div class="text-center" style="margin-bottom: 20px;">
              <th class="text-start border-0" style="background: rgba(255,255,255,0);" colspan="5"><button id="add-row"
                  class="btn btn-outline-success btn-sm fw-bold border rounded-pill border-1 border-success"
                  type="button" style="width: 30%;padding: 0;margin-bottom: 9px;margin-top: 8px;"><svg
                    xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16"
                    class="bi bi-plus-lg">
                    <path fill-rule="evenodd"
                      d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2">
                    </path>
                  </svg>&nbsp;Nouvelle ligne</button>

              </th>
            </div>
          </div>
          <div class="text-center" style="margin-bottom: 20px;">
            <table class="table table-sm table-borderless">
              <tr>
                <th class="text-capitalize border-2 border-dark" style="background: rgba(255,255,255,0);" colspan="3"
                  rowspan="2">Arreté La présente facture à la somme de :<br>#...Trente mille six cents Dirhams...#</th>
                <th class="text-uppercase border-2 border-dark" style="background: rgba(255,255,255,0);">total ht</th>
                <th class="text-uppercase border-2 border-dark" style="background: rgba(255,255,255,0);">tva</th>
                <th class="text-uppercase border-2 border-dark" style="background: rgba(255,255,255,0);">total</th>
              </tr>

              <tr>
                <td class="border-2 border-dark" style="background: rgba(255,255,255,0);"> <input type="text"
                    name="total_without_tax" id="total_without_tax" readonly> </td>
                <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                  <div class="input-group"><input class="form-control" type="number" value="20" id="tax"
                      name="tax"><span class="input-group-text">%</span></div>
                </td>
                <td class="border-2 border-dark" style="background: rgba(255,255,255,0);"><input type="text"
                    name="total_with_tax" id="total_with_tax" readonly> </td>
              </tr>
            </table>
          </div>
          <p class="fw-bold">Pièces Jointes :</p><button id="upload-button"
            class="btn btn-outline-success btn-sm text-nowrap fw-bold border rounded-pill border-1 border-success"
            type="button" style="width: 30%;padding: 0px;margin-bottom: 15px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16"
              class="bi bi-cloud-upload-fill">
              <path fill-rule="evenodd"
                d="M8 0a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 4.095 0 5.555 0 7.318 0 9.366 1.708 11 3.781 11H7.5V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V11h4.188C14.502 11 16 9.57 16 7.773c0-1.636-1.242-2.969-2.834-3.194C12.923 1.999 10.69 0 8 0m-.5 14.5V11h1v3.5a.5.5 0 0 1-1 0">
              </path>
            </svg>&nbsp;Ajouter des Pièces Jointes
          </button>
          <input type="file" name="doc" id="doc" style="display: none" multiple>
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
              3341831
              / ICE :&nbsp; 000095738000027/ RC : 16137 CNSS : 8712863&nbsp;<br>Patente : 28565292 / Capitale : 100
              000,00
              Gsm : 06 98 46 33 60 - 06 61 78 99 70<br>E-mail :&nbsp;contact@oufoqalbinae.com</p>
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
</style>
<link rel="stylesheet"
  href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&amp;display=swap">

@endpush

@push('scripts')
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
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    let index = 0;

      const addRowButton = document.getElementById('add-row');
      const tableBody = document.getElementById('invoice-table-body');
      const uploadButton = document.getElementById('upload-button');
      const fileInput = document.getElementById('doc');
      const fileList = document.getElementById('file-list');
      const totalWithoutTaxSpan = document.getElementById('total_without_tax');
      const taxInput = document.getElementById('tax');
      uploadButton.addEventListener('click', function () {
                fileInput.click();
            });
      
      fileInput.addEventListener('change', function () {
          fileList.innerHTML = ''; // Clear the file list
          for (let i = 0; i < fileInput.files.length; i++) {
              const fileBox = document.createElement('div');
              fileBox.className = 'file-box';
              fileBox.textContent = fileInput.files[i].name;
              fileList.appendChild(fileBox);
          }
      });

      // generate number and appand it to the span with number id
      const number = Math.floor(Math.random() * 1000000);
      document.getElementById('number').value = number;

      // Calculate the total price of the row
      

    

      addRowButton.addEventListener('click', function () {
        const ref = Math.floor(Math.random() * 1000000);
          const newRow = document.createElement('tr');
          newRow.innerHTML = `
             <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                <div class="input-group"><input class="form-control" type="text" name="ref[${index}]" value="${ref}"></div>
            </th>
            <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                <div class="input-group"><textarea class="form-control" rows="3" name="name[${index}]"></textarea></div>
            </th>
            <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                <div class="input-group"><input class="form-control" type="number" name="qte[${index}]"></div>
            </th>
            <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                <div class="input-group"><input class="form-control" type="number"  name="prix_unite[${index}]"></div>
            </th>
            <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                <div class="input-group"><textarea class="form-control" rows="3" name="category[${index}]"></textarea></div>
            </th>
            <th class="border-2 border-dark" style="background: rgba(255,255,255,0);"><input class="form-control" type="number" name="total_price_unite[${index}]"></th>
            <th style="background: rgba(255,255,255,0);"><button class="btn btn-outline-danger delete-row" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-trash-fill">
            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"></path>
            </svg></button></th>
          `;

         
       
          
          tableBody.appendChild(newRow);
          index ++ ;
          console.log(index);
          const quantityInput = newRow.querySelector(`input[name="qte[${index - 1}]"]`);
          const unitPriceInput = newRow.querySelector(`input[name="prix_unite[${index - 1}]"]`);
          const totalPriceSpan = newRow.querySelector(`input[name="total_price_unite[${index - 1}]"]`);
          
          function calculateTotalPrice(row) {
        const quantity = parseFloat(quantityInput.value) || 0;
        const unitPrice = parseFloat(unitPriceInput.value) || 0;
        const totalPrice = quantity * unitPrice;
        totalPriceSpan.value = totalPrice.toFixed(2);
        calculateTotalOrderPrice();
        }
        function calculateTotalOrderPrice() {
        let totalWithoutTax = 0;
        const totalPriceSpans = tableBody.querySelectorAll(`input[name="total_price_unite[${index - 1}]"]`);

        totalPriceSpans.forEach(span => {
            totalWithoutTax += parseFloat(span.value) || 0;
        });
        // console.log(totalWithoutTax,totalPriceSpans);
        
        totalWithoutTaxSpan.value = totalWithoutTax.toFixed(2);
        calculateTotalWithTax(totalWithoutTax);
    }

        function calculateTotalWithTax(totalWithoutTax) {
            const tax = parseFloat(taxInput.value) || 0;
            const totalWithTax = totalWithoutTax + (totalWithoutTax * (tax / 100));
            document.getElementById('total_with_tax').value = totalWithTax.toFixed(2);
        }
              quantityInput.addEventListener('input', function () {
                  calculateTotalPrice(newRow);
              });

              unitPriceInput.addEventListener('input', function () {
                  calculateTotalPrice(newRow);
              });
              taxInput.addEventListener('input', function () {
        const totalWithoutTax = parseFloat(totalWithoutTaxSpan.value) || 0;
        calculateTotalWithTax(totalWithoutTax);
    });
      });

      tableBody.addEventListener('click', function (event) {
          if (event.target.classList.contains('delete-row')) {
              const row = event.target.closest('tr');
              row.remove();
              calculateTotalOrderPrice();
          }
      });
      
      
    
  });
</script>
@endpush