@extends('layouts.app')
@section('title', 'Edit Delivery')
@section('content')
<x-Breadcrumb title="Edit Delivery" />
<div class="row">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('delivery.update', $delivery->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{$delivery->type}}">
                <input type="hidden" name="tax_type" value="{{$delivery->tax_type}}">
                <div class="container shadow"
                    style="margin-top: 46px;background: url(&quot;{{asset('assets/invoice_asset/img/Oufoq%20albinae%20BIG.png')}}&quot;) center / cover no-repeat;border-radius: 12px;min-height: 1000px;">
                    <div class="vstack">
                        <div class="row d-flex d-xl-flex align-items-center align-items-xl-center">
                            <div class="col"><img src="{{asset('assets/invoice_asset/img/logo%20big.png')}}"
                                    style="width: 150px;background: rgba(255,255,255,0);"></div>
                            <div class="col">
                                <h4 class="text-capitalize text-center">bon de livraison N°#
                                    <input type="text" name="number" id="number" value="{{ $delivery->number }}">
                                </h4>
                            </div>
                            <div class="col text-center">
                                <span class="fw-bold" style="margin-right: 22px;">Salé</span>
                                <span>Le <input class="border-0 focus-ring form-control-sm" type="date"
                                        style="width: 120px;" name="date" value="{{ $delivery->date }}"></span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <table class="table table-sm table-borderless">
                            <thead>
                                <tr class="text-uppercase text-center">
                                    @if($delivery->type == 'supplier')
                                    <th
                                        style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-bottom-style: none;width: 155px;">
                                        Supplier</th>
                                    @endif
                                    <th
                                        style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-bottom-style: none;width: 155px;">
                                        N° Client</th>
                                    <th
                                        style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-bottom-style: none;width: 175px;">
                                        Projet</th>
                                    <th
                                        style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-bottom-style: none;width: 175px;">
                                        Mode Reglement</th>
                                    <th
                                        style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-bottom-style: none;width: 175px;">
                                        Facture</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-uppercase text-center">
                                    @if($delivery->type == 'supplier')
                                    <td
                                        style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-top-style: none;">
                                        <div class="input-group">
                                            <select class="bg-transparent border-0 focus-ring form-select"
                                                id="supplier_id" name="supplier_id">
                                                @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" {{ $supplier->id ==
                                                    $delivery->supplier_id ? 'selected' : '' }}>
                                                    {{ $supplier->full_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    @endif
                                    <td
                                        style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-top-style: none;">
                                        <div class="input-group">
                                            <select class="bg-transparent border-0 focus-ring form-select"
                                                id="client_id" name="client_id">
                                                @foreach($clients as $client)
                                                <option value="{{ $client->id }}" {{ $client->id == $delivery->client_id
                                                    ? 'selected' : '' }}>
                                                    {{ $client->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td
                                        style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-top-style: none;">
                                        <div class="input-group">
                                            <select class="bg-transparent border-0 focus-ring form-select"
                                                id="project_id" name="project_id">
                                                <option value="{{ $delivery->project_id }}" selected>{{
                                                    $delivery->project->name }}</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td
                                        style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-top-style: none;">
                                        <div class="input-group">
                                            <select class="bg-transparent border-0 focus-ring form-select"
                                                id="payment_method" name="payment_method">
                                                <option value="bank_transfer" {{ $delivery->payment_method ==
                                                    'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                                <option value="cheque" {{ $delivery->payment_method == 'cheque' ?
                                                    'selected' : '' }}>Chèque</option>
                                                <option value="credit" {{ $delivery->payment_method == 'credit' ?
                                                    'selected' : '' }}>Credit</option>
                                                <option value="cash" {{ $delivery->payment_method == 'cash' ? 'selected'
                                                    : '' }}>Cash</option>
                                                <option value="traita" {{ $delivery->payment_method == 'traita' ?
                                                    'selected' : '' }}>Traita</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td
                                        style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-top-style: none;">
                                        <div class="input-group">
                                            <select class="bg-transparent border-0 focus-ring form-select" id="facture"
                                                name="facture">
                                                <option value="true" {{ $delivery->facture ? 'selected' : '' }}>Yes
                                                </option>
                                                <option value="false" {{ !$delivery->facture ? 'selected' : '' }}>No
                                                </option>
                                            </select>
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
                                <!-- Items will be added here dynamically -->
                            </tbody>
                        </table>
                        <div class="text-center" style="margin-bottom: 20px;">
                            <button id="add-row"
                                class="btn btn-outline-success btn-sm fw-bold border rounded-pill border-1 border-success"
                                type="button" style="width: 30%;padding: 0;margin-bottom: 9px;margin-top: 8px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor"
                                    viewBox="0 0 16 16" class="bi bi-plus-lg">
                                    <path fill-rule="evenodd"
                                        d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2">
                                    </path>
                                </svg>&nbsp;Nouvelle ligne
                            </button>
                        </div>
                    </div>
                    <div class="text-center" style="margin-bottom: 20px;">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th class="text-capitalize border-2 border-dark"
                                    style="background: rgba(255,255,255,0);" colspan="3" rowspan="2">Arreté La présente
                                    facture à la somme de :<br>#... <span id="numberToWord"></span> ...#</th>
                                <th class="text-uppercase border-2 border-dark"
                                    style="background: rgba(255,255,255,0);">total ht</th>
                                <th class="text-uppercase border-2 border-dark"
                                    style="background: rgba(255,255,255,0);">
                                    @if($delivery->tax_type == 'normal')
                                    tva (20%)
                                    @elseif($delivery->tax_type == 'included')
                                    TVA (20% Inclus)
                                    @elseif($delivery->tax_type == 'no_tax')
                                    TVA (No Tax)
                                    @endif
                                </th>
                                <th class="text-uppercase border-2 border-dark"
                                    style="background: rgba(255,255,255,0);">total</th>
                            </tr>
                            <tr>
                                <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                                    <input type="number" name="total_without_tax" id="total_without_tax"
                                        value="{{ $delivery->total_without_tax }}" readonly>
                                </td>
                                <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                                    <input type="number" name="tax" id="tax" value="{{ $delivery->tax }}" readonly>
                                </td>
                                <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                                    <input type="number" name="total_with_tax" id="total_with_tax"
                                        value="{{ $delivery->total_with_tax }}" readonly>
                                </td>
                            </tr>
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
                    <div id="file-list">
                        @if($delivery->doc)
                        <div class="file-box">Current file: {{ basename($delivery->doc) }}</div>
                        @endif
                    </div>
                    <div class="text-center" style="margin-bottom: 20px;">
                        <h3>Remarques</h3>
                        <div class="input-group">
                            <textarea class="shadow-sm form-control" rows="12"
                                style="height: auto;margin-bottom: 30px;border-radius: 10px;"
                                name="note">{{ $delivery->note }}</textarea>
                        </div>
                    </div>
                    <div class="text-center" style="margin-bottom: 20px;">
                        <button
                            class="btn btn-outline-success btn-sm fw-bold border rounded-pill border-1 border-success"
                            type="submit" style="width: 30%;padding: 0px;margin-bottom: 15px;">Update</button>
                    </div>
                    <div class="vstack">
                        <div
                            style="width: 100%;height: 3px;background: #ed961c;border-radius: 26px;margin-bottom: 5px;">
                        </div>
                        <p class="fw-bold text-center" style="font-size: 13px;">
                            Adresse : {{ $company->address }}<br>
                            IF : {{ $company->if }} / ICE : {{ $company->ice }} / RC : {{ $company->rc }} / CNSS : {{
                            $company->cnss }}<br>
                            Patente : {{ $company->patente }} / Capitale : {{ number_format($company->capital, 2) }}<br>
                            Gsm : {{ $company->phone1 }} - {{ $company->phone2 }}<br>
                            E-mail : {{ $company->email }}
                        </p>
                        <p class="text-capitalize text-center text-muted">Merci de Votre Confiance</p>
                    </div>
                </div>
            </form>
        </div>
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
                    $('#project_id').empty();
                    $.each(data, function(key, project) {
                        var selected = (project.id == {{ $delivery->project_id }}) ? 'selected' : '';
                        $('#project_id').append('<option value="' + project.id + '" ' + selected + '>' + project.name + '</option>');
                    });
                }
            });
        }
    });

    function initializeSelect2() {
        $('#supplier_id, #client_id, #project_id').select2({
            placeholder: 'Select an option',
            allowClear: true
        });
    }

    initializeSelect2();
});

document.addEventListener('DOMContentLoaded', function () {
    const elements = {
        addRowButton: document.getElementById('add-row'),
        tableBody: document.getElementById('invoice-table-body'),
        uploadButton: document.getElementById('upload-button'),
        fileInput: document.getElementById('doc'),
        fileList: document.getElementById('file-list'),
        totalWithoutTaxInput: document.getElementById('total_without_tax'),
        taxInput: document.getElementById('tax'),
        totalWithTaxInput: document.getElementById('total_with_tax'),
        numberToWord: document.getElementById('numberToWord')
    };

    function setupEventListeners() {
        elements.addRowButton.addEventListener('click', addNewRow);
        elements.tableBody.addEventListener('click', handleRowDelete);
        elements.tableBody.addEventListener('input', handleRowInput);
        elements.uploadButton.addEventListener('click', () => elements.fileInput.click());
        elements.fileInput.addEventListener('change', updateFileList);
    }

    function createRowElement(item = null) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                <input class="form-control" type="text" name="ref[]" value="${item ? item.ref : Math.floor(Math.random() * 1000000)}">
            </td>
            <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                <textarea class="form-control" rows="3" name="name[]">${item ? item.name : ''}</textarea>
            </td>
            <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                <input class="form-control quantity" type="number" name="qte[]" min="0" step="0.01" value="${item ? item.qte : ''}">
            </td>
            <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                <input class="form-control unit-price" type="number" step="0.01" name="prix_unite[]" min="0" value="${item ? item.prix_unite : ''}">
            </td>
            <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                <textarea class="form-control" rows="3" name="category[]">${item ? item.category : ''}</textarea>
            </td>
            <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                <input class="form-control total-price" type="number" name="total_price_unite[]" readonly value="${item ? item.total_price_unite : ''}">
            </td>
            <td style="background: rgba(255,255,255,0);">
                <button class="btn btn-outline-danger delete-row" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-trash-fill">
                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"></path>
                    </svg>
                </button>
            </td>
        `;
        return row;
    }

    // Load existing items
    @foreach($delivery->items as $item)
        elements.tableBody.appendChild(createRowElement({
            ref: '{{ $item->ref }}',
            name: '{{ $item->name }}',
            qte: {{ $item->qte }},
            prix_unite: {{ $item->prix_unite }},
            category: '{{ $item->category }}',
            total_price_unite: {{ $item->total_price_unite }}
        }));
    @endforeach

    function addNewRow() {
        elements.tableBody.appendChild(createRowElement());
    }

    function handleRowDelete(event) {
        if (event.target.closest('.delete-row')) {
            event.target.closest('tr').remove();
            recalculateTotals();
        }
    }

    function handleRowInput(event) {
        if (event.target.classList.contains('quantity') || event.target.classList.contains('unit-price')) {
            updateRowTotal(event.target.closest('tr'));
            recalculateTotals();
        }
    }

    function updateRowTotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const totalPrice = quantity * unitPrice;
        row.querySelector('.total-price').value = totalPrice.toFixed(2);
    }

    function recalculateTotals() {
        const totalWithoutTax = calculateTotalWithoutTax();
        const taxType = '{{ $delivery->tax_type }}';
        
        elements.totalWithoutTaxInput.value = totalWithoutTax.toFixed(2);
        
        let tax = 0;
        let totalWithTax = totalWithoutTax;
        
        switch(taxType) {
            case 'normal':
                tax = totalWithoutTax * 0.20;
                totalWithTax = totalWithoutTax + tax;
                elements.taxInput.removeAttribute('readonly');
                break;
            
            case 'included':
                tax = (totalWithoutTax / 1.20) * 0.20;
                totalWithTax = totalWithoutTax;
                elements.totalWithoutTaxInput.value = (totalWithoutTax - tax).toFixed(2);
                elements.taxInput.setAttribute('readonly', 'readonly');
                break;
            
            case 'no_tax':
                tax = 0;
                totalWithTax = totalWithoutTax;
                elements.taxInput.setAttribute('readonly', 'readonly');
                break;
        }
        
        elements.taxInput.value = tax.toFixed(2);
        elements.totalWithTaxInput.value = totalWithTax.toFixed(2);
        updateNumberToWord(totalWithTax);
    }

    function calculateTotalWithoutTax() {
        return Array.from(elements.tableBody.querySelectorAll('.total-price'))
            .reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);
    }

    function updateNumberToWord(totalWithTax) {
        fetch(`/dashboard/order/delivery/${totalWithTax}/to-number`)
            .then(response => response.json())
            .then(data => {
                elements.numberToWord.textContent = data;
            })
            .catch(error => console.error('Error:', error));
    }

    function updateFileList() {
        elements.fileList.innerHTML = '';
        Array.from(elements.fileInput.files).forEach(file => {
            const fileBox = document.createElement('div');
            fileBox.className = 'file-box';
            fileBox.textContent = file.name;
            elements.fileList.appendChild(fileBox);
        });
    }

    setupEventListeners();
    updateNumberToWord({{ $delivery->total_with_tax }});
});
</script>
@endpush