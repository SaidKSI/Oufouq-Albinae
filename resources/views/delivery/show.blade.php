@extends('layouts.app')
@section('title', 'Delivery')
@section('content')
<x-Breadcrumb title="Delivery" />
<div class="row">
  <div class="card">
    <div class="card-body">
      <div class="container shadow"
        style="margin-top: 46px;background: url(&quot;{{asset('assets/invoice_asset/img/Oufoq%20albinae%20BIG.png')}}&quot;) center / cover no-repeat;border-radius: 12px;min-height: 1000px;">
        <div class="vstack">
          <div class="row d-flex d-xl-flex align-items-center align-items-xl-center">
            <div class="col"><img src="{{asset('assets/invoice_asset/img/logo%20big.png')}}"
                style="width: 150px;background: rgba(255,255,255,0);"></div>
            <div class="col">
              <h4 class="text-capitalize text-center">bon de livraison N°# {{$delivery->number}}</h4>
            </div>
            <div class="col text-center"><span class="fw-bold" style="margin-right: 22px;">Salé</span><span>Le
                {{$delivery->date}}</span>
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
              </tr>
            </thead>
            <tbody>
              <tr class="text-uppercase text-center">
                @if($delivery->type == 'supplier')
                <td style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0) ;border-top-style: none;">
                  <div class="input-group">
                    {{$delivery->supplier->full_name}}</div>
                </td>
                @endif
                <td style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0) ;border-top-style: none;">
                  <div class="input-group">
                    {{$delivery->client->name}}
                  </div>
                </td>
                <td style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0) ;border-top-style: none;">
                  <div class="input-group"> {{$delivery->project->name}}</div>
                </td>
                <td style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0) ;border-top-style: none;">
                  <div class="input-group"> {{$delivery->payment_method}}</div>
                </td>
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
              @foreach($delivery->items as $item)
              <tr class="text-uppercase">
                <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">{{$item->ref}}</td>
                <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">{{$item->name}}</td>
                <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">{{$item->qte}}
                </td>
                <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">{{$item->prix_unite}}</td>
                <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">{{$item->category}}
                </td>
                <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">{{$item->total_price_unite}}
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="text-center" style="margin-bottom: 20px;">
          <table class="table table-sm table-borderless">
            <tr>
              <th class="text-capitalize border-2 border-dark" style="background: rgba(255,255,255,0);" colspan="3"
                rowspan="2">Arreté La présente facture à la somme de :<br>#...{{$totalInAlphabet}}...#</th>
              <th class="text-uppercase border-2 border-dark" style="background: rgba(255,255,255,0);">total ht</th>
              <th class="text-uppercase border-2 border-dark" style="background: rgba(255,255,255,0);">tva (20%)</th>
              <th class="text-uppercase border-2 border-dark" style="background: rgba(255,255,255,0);">total</th>
            </tr>

            <tr>
              <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                {{$delivery->total_without_tax}}
              </td>
              <td class="border-2 border-dark" style="background: rgba(255,255,255,0);">
                <div class="input-group"> {{$delivery->tax}}%</div>
              </td>
              <td class="border-2 border-dark" style="background: rgba(255,255,255,0);"> {{$delivery->total_with_tax}}
              </td>
            </tr>
          </table>
        </div>
        @if($delivery->doc)
        <p class="fw-bold">Pièces Jointes :</p>
        <a href="{{ asset('storage/' . $delivery->doc) }}" target="_blank"><i
            class="ri-attachment-fill fs-1 mx-5"></i></a>
        @endif
        <input type="file" name="doc" id="doc" style="display: none" multiple>
        <div id="file-list"></div>
        <div class="text-center" style="margin-bottom: 20px;">
          <h3>Remarques</h3>
          <div class="input-group"><textarea class="shadow-sm form-control" rows="12" style="height: auto"
              style="margin-bottom: 30px;border-radius: 10px;" name="note" readonly> {{$delivery->note}}</textarea>
          </div>
        </div>
        <div class="text-center" style="margin-bottom: 20px;">
          <button class="btn btn-outline-success btn-sm fw-bold border rounded-pill border-1 border-success"
            type="submit" style="width: 30%;padding: 0px;margin-bottom: 15px;">Print</button>
        </div>

        <div class="vstack">
          <div style="width: 100%;height: 3px;background: #ed961c;border-radius: 26px;margin-bottom: 5px;"></div>
          <p class="fw-bold text-center" style="font-size: 13px;">Adresse : {{$company->address}} / IF :
            {{$company->if}} / ICE :&nbsp; {{$company->ice}}/ RC : {{$company->ice}} CNSS :
            {{$company->cnss}}&nbsp;<br>Patente : {{$company->patente}} / Capitale : {{
            number_format($company->capital, 2) }} Gsm : {{$company->phone1}} -
            {{$company->phone2}}<br>E-mail :&nbsp;{{$company->email}}</p>
          <p class="text-capitalize text-center text-muted">Merci de Votre Confiance</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')

@endpush