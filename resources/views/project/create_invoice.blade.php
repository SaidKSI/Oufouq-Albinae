@extends('layouts.app')
@section('title', 'Create Invoice')
@section('content')
<x-Breadcrumb title="Create Invoice" />
<div class="row bg-secondary">
  <div class="card">
    <form action="{{ route('project.store_invoice') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="estimate_id" value="{{ $estimate->id }}">
      <div class="card-body">
        <div class="container shadow"
          style="margin-top: 46px;background: url(&quot;{{asset('assets/invoice_asset/img/Oufoq%20albinae%20BIG.png')}}&quot;) center / cover no-repeat;border-radius: 12px;min-height: 1000px;">
          <div class="vstack">
            <div class="row d-flex d-xl-flex align-items-center align-items-xl-center">
              <div class="col"><img src="{{asset('assets/invoice_asset/img/logo%20big.png')}}"
                  style="width: 150px;background: rgba(255,255,255,0);"></div>
              <div class="col">
                <h4 class="text-capitalize text-center">Facture N°# <input type="text" name="number" id="number" value="{{ $estimate->number }}"> </h4>
              </div>
              <div class="col text-center"><span class="fw-bold" style="margin-right: 22px;">Salé</span><span>Le <input
                    class="border-0 focus-ring form-control-sm" type="date" style="width: 120px;" name="date"
                    value="{{ $estimate->date ?? now()->format('Y-m-d') }}"></span></div>
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
                      
                       <input type="text" value="{{ $estimate->project->client->name }}" class="form-control" readonly>
                    </div>
                  </td>
                  <td style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0) ;border-top-style: none;">
                    <div class="input-group">
                      <input type="text" value="{{ $estimate->project->name }}" class="form-control" readonly>
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
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">Reference</th>
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">Quantity</th>
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">Total HT</th>
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">TVA</th>
                  <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">Total</th>
                </tr>
              </thead>
              <tbody id="invoice-table-body">
                <tr>
                  <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                    <input class="form-control" type="text" name="ref" value="{{ $estimate->reference }}">
                  </td>
                  <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                    <input class="form-control quantity" type="number" name="qte" value="{{ $estimate->quantity }}">
                  </td>
                  <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                    <input type="text" class="form-control" name="total_without_tax" id="total_without_tax" value="{{ $estimate->total_price }}" readonly>
                  </td>
                  <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                    <div class="input-group">
                      <input class="form-control" type="number" value="{{ $estimate->tax }}" id="tax" name="tax" readonly>
                      <span class="input-group-text">%</span>
                    </div>
                  </td>
                  <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                    <input type="text" class="form-control" name="total_with_tax" id="total_with_tax" value="{{ $estimate->total_price + ($estimate->total_price * $estimate->tax / 100) }}" readonly>
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <th class="text-capitalize border-2 border-dark" style="background: rgba(255,255,255,0);" colspan="5">
                    Arreté La présente facture à la somme de :<br>#... <span id="numberToWord"></span> ...#
                  </th>
                </tr>
              </tfoot>
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
          @if ($estimate->documents->count() > 0)
          <p class="fw-bold">Pièces Jointes :</p>
          @foreach ($estimate->documents as $document)
          <a href="{{ asset('storage/' . $document->path) }}" target="_blank"><p>{{ $document->name }}</p></a>
          @endforeach
          @endif
          <input type="file" name="doc" id="doc" style="display: none" multiple accept="image/*">
          <div id="file-list"></div>
          <div class="text-center" style="margin-bottom: 20px;">
            <h3>Remarques</h3>
            <div class="input-group"><textarea class="shadow-sm form-control" rows="12" style="height: auto"
                style="margin-bottom: 30px;border-radius: 10px;" name="note">{{ $estimate->note }}</textarea></div>
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
</style>
<link rel="stylesheet"
  href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&amp;display=swap">
@endpush

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
     function updateNumberToWord() {
      const totalWithTax = document.getElementById('total_with_tax').value;
        fetch(`/dashboard/order/delivery/${totalWithTax}/to-number`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('numberToWord').textContent = data;
            })
            .catch(error => console.error('Error:', error));
    }
    updateNumberToWord();
  });
</script>
@endpush

