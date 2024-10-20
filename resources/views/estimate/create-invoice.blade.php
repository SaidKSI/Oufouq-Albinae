@extends('layouts.app')
@section('title', 'Create Estimate Invoice')
@section('content')
<x-Breadcrumb title="Create Estimate Invoice" />
<div class="row bg-secondary">
    <div class="card">
        <form action="{{ route('project-estimate.store-invoice') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="container shadow"
                    style="margin-top: 46px;background: url(&quot;{{asset('assets/invoice_asset/img/Oufoq%20albinae%20BIG.png')}}&quot;) center / cover no-repeat;border-radius: 12px;min-height: 1000px;">
                    <div class="vstack">
                        <div class="row d-flex d-xl-flex align-items-center align-items-xl-center">
                            <div class="col"><img src="{{asset('assets/invoice_asset/img/logo%20big.png')}}"
                                    style="width: 150px;background: rgba(255,255,255,0);"></div>
                            <div class="col">
                                <h4 class="text-capitalize text-center">Facture N°# <input type="text" name="number"
                                        id="number"> </h4>
                            </div>
                            <div class="col text-center"><span class="fw-bold"
                                    style="margin-right: 22px;">Salé</span><span>Le <input
                                        class="border-0 focus-ring form-control-sm" type="date" style="width: 120px;"
                                        name="date" value="{{now()->format('Y-m-d')}}"></span></div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <table class="table table-sm table-borderless">
                            <thead>
                                <tr class="text-uppercase text-center">
                                    <th
                                        style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-bottom-style: none;width: 155px;">
                                        Client</th>
                                    <th
                                        style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-bottom-style: none;width: 175px;">
                                        Projet</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-uppercase text-center">
                                    <td
                                        style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0) ;border-top-style: none;">
                                        <div class="input-group">
                                            <select class="bg-transparent border-0 focus-ring form-select"
                                                id="client_id" name="client_id">
                                                <option disabled selected>Select a Client</option>
                                                @foreach($clients as $client)
                                                <option value="{{ $client->id }}">{{ $client->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td
                                        style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0) ;border-top-style: none;">
                                        <div class="input-group">
                                            <select class="bg-transparent border-0 focus-ring form-select"
                                                id="project_id" name="project_id">
                                                @if($selectedProjectId)
                                                <option value="{{ $selectedProjectId }}" selected>
                                                    {{ App\Models\Project::find($selectedProjectId)->name }}
                                                </option>
                                                @else
                                                <option disabled selected>Select a Project</option>
                                                @endif
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
                                    <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">Reference
                                    </th>
                                    <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">Quantity
                                    </th>
                                    <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">Total HT
                                    </th>
                                    <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">TVA</th>
                                    <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">Total</th>
                                </tr>
                            </thead>
                            <tbody id="invoice-table-body">
                                <tr>
                                    <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                                        <input class="form-control" type="text" name="reference"
                                            value="{{ rand(100000, 999999) }}">
                                    </td>
                                    <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                                        <input class="form-control quantity" type="number" name="qte" value="1">
                                    </td>
                                    <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                                        <input type="number" step="0.01" class="form-control" name="total_without_tax" id="total_without_tax">
                                    </td>
                                    <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                                        <div class="input-group">
                                            <input class="form-control" type="number" value="20" id="tax" name="tax">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </td>
                                    <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                                        <input type="text" class="form-control" name="total_with_tax"
                                            id="total_with_tax" readonly>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-capitalize border-2 border-dark"
                                        style="background: rgba(255,255,255,0);" colspan="5">
                                        Arreté La présente facture à la somme de :<br>#... <span
                                            id="numberToWord"></span> ...#
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <p class="fw-bold">Pièces Jointes :</p>
                    <button id="upload-button"
                        class="btn btn-outline-success btn-sm text-nowrap fw-bold border rounded-pill border-1 border-success"
                        type="button" style="width: 30%;padding: 0px;margin-bottom: 15px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor"
                            viewBox="0 0 16 16" class="bi bi-cloud-upload-fill">
                            <path fill-rule="evenodd"
                                d="M8 0a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 4.095 0 5.555 0 7.318 0 9.366 1.708 11 3.781 11H7.5V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V11h4.188C14.502 11 16 9.57 16 7.773c0-1.636-1.242-2.969-2.834-3.194C12.923 1.999 10.69 0 8 0m-.5 14.5V11h1v3.5a.5.5 0 0 1-1 0">
                            </path>
                        </svg>&nbsp;Ajouter des Pièces Jointes
                    </button>
                    <input type="file" name="doc" id="doc" style="display: none" multiple accept="image/*">
                    <div id="file-list"></div>
                    <div class="text-center" style="margin-bottom: 20px;">
                        <h3>Remarques</h3>
                        <div class="input-group"><textarea class="shadow-sm form-control" rows="12" style="height: auto"
                                style="margin-bottom: 30px;border-radius: 10px;" name="note"></textarea></div>
                    </div>
                    <div class="text-center" style="margin-bottom: 20px;">
                        <button
                            class="btn btn-outline-success btn-sm fw-bold border rounded-pill border-1 border-success"
                            type="submit" style="width: 30%;padding: 0px;margin-bottom: 15px;">Enregistrer</button>
                    </div>
                    <div class="vstack">
                        <div
                            style="width: 100%;height: 3px;background: #ed961c;border-radius: 26px;margin-bottom: 5px;">
                        </div>
                        <p class="fw-bold text-center" style="font-size: 13px;">Adresse : N°97 Rue Assila Laayayda Salé
                            / IF :
                            3341831 / ICE :&nbsp; 000095738000027/ RC : 16137 CNSS : 8712863&nbsp;<br>Patente : 28565292
                            / Capitale :
                            100 000,00 Gsm : 06 98 46 33 60 - 06 61 78 99 70<br>E-mail :&nbsp;contact@oufoqalbinae.com
                        </p>
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
    function initializeSelect2() {
        $('#supplier_id, #client_id, #project_id').select2({
            placeholder: 'Select an option',
            allowClear: true
        });
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
                          var selected = (project.id == {{ $selectedProjectId ?? 'null' }}) ? 'selected' : '';
                          $('#project_id').append('<option value="' + project.id + '" ' + selected + '>' + project.name + '</option>');
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
    }

    initializeSelect2();

  });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const elements = {
        uploadButton: document.getElementById('upload-button'),
        fileInput: document.getElementById('doc'),
        fileList: document.getElementById('file-list'),
        totalWithoutTaxInput: document.getElementById('total_without_tax'),
        taxInput: document.getElementById('tax'),
        totalWithTaxInput: document.getElementById('total_with_tax'),
        numberInput: document.getElementById('number'),
        numberToWord: document.getElementById('numberToWord'),
        quantityInput: document.querySelector('input[name="qte"]'),
        refInput: document.querySelector('input[name="reference"]'),
    };

    function initializeInvoice() {
        setupFileUpload();
        generateRandomNumber();
        setupEventListeners();
    }

    function setupFileUpload() {
        elements.uploadButton.addEventListener('click', () => elements.fileInput.click());
        elements.fileInput.addEventListener('change', updateFileList);
    }

    function updateFileList() {
        elements.fileList.innerHTML = '';
        Array.from(elements.fileInput.files).forEach(file => {
            const fileBox = createFileBox(file.name);
            elements.fileList.appendChild(fileBox);
        });
    }

    function createFileBox(fileName) {
        const fileBox = document.createElement('div');
        fileBox.className = 'file-box';
        fileBox.textContent = fileName;
        return fileBox;
    }

    function generateRandomNumber() {
        elements.numberInput.value = Math.floor(Math.random() * 1000000);
        elements.refInput.value = Math.floor(Math.random() * 1000000);
    }

    function setupEventListeners() {
        elements.totalWithoutTaxInput.addEventListener('input', updateTotals);
        elements.taxInput.addEventListener('input', updateTotals);
    }

    function updateTotals() {
        const totalWithoutTax = parseFloat(elements.totalWithoutTaxInput.value) || 0;
        const tax = parseFloat(elements.taxInput.value) || 0;
        const totalWithTax = totalWithoutTax + (totalWithoutTax * (tax / 100));

        elements.totalWithTaxInput.value = totalWithTax.toFixed(2);

        updateNumberToWord(totalWithTax);
    }

    function updateNumberToWord(totalWithTax) {
        fetch(`/dashboard/order/delivery/${totalWithTax}/to-number`)
            .then(response => response.json())
            .then(data => {
                elements.numberToWord.textContent = data;
            })
            .catch(error => console.error('Error:', error));
    }

    initializeInvoice();
});
</script>
@endpush
