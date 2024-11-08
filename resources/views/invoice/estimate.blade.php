<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Devis #{{ $estimate->number }}</title>
  <style>
    body {
      font-family: 'Open Sans', sans-serif;
      background: url("{{asset('assets/invoice_asset/img/Oufoq%20albinae%20BIG.png')}}") center / cover no-repeat;
      min-height: 100vh;
    }

    .container {
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: 12px;
      padding: 20px;
      max-width: 800px;
      margin: 0 auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th,
    td {
      border: 2px solid #000;
      padding: 8px;
      text-align: center;
    }

    .footer {
      margin-top: 20px;
      text-align: center;
      font-size: 13px;
    }

    .footer-line {
      width: 100%;
      height: 3px;
      background: #ed961c;
      border-radius: 26px;
      margin-bottom: 5px;
    }

    .text-center {
      text-align: center;
    }

    .items-table th,
    .items-table td {
      text-align: left;
    }

    @media print {
      body {
        background: none;
      }

      .container {
        background: none;
      }

      .no-print {
        display: none;
      }
    }

    .header-container {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .logo-section {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .logo {
      width: 150px;
      margin-bottom: 10px;
    }

    .devis-number {
      font-size: 1.2rem;
      font-weight: bold;
      margin-top: 5px;
    }

    .date-section {
      text-align: right;
      margin-right: 100px;
    }

    .location-date {
      margin-top: 70px;
      margin-right: 55px;
    }

    .client-info {
      border: 5px solid #000;
      padding: 8px 15px;
      min-width: 200px;
      text-align: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
      min-height: 60px;
    }

    .client-info div {
      margin: 2px 0;
    }

    .city {
      font-weight: bold;
      margin-right: 10px;
    }

    .totals-table {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
    }

    .totals-table td {
      border: 2px solid #000;
      padding: 8px 15px;
    }

    .amount-in-words {
      text-align: left;
      padding: 15px;
      font-weight: bold;
      font-style: italic;
      width: 60%;
      vertical-align: middle;
    }

    .amount-in-words span {
      text-transform: uppercase;
    }

    .totals-column {
      width: 40%;
    }

    .totals-row {
      display: flex;
      justify-content: space-between;
      padding: 5px 0;
      border-bottom: 1px solid #000;
    }

    .totals-row:last-child {
      border-bottom: none;
    }

    .totals-label {
      font-weight: bold;
      text-transform: uppercase;
    }
  </style>
</head>

<body>
  <div class="vstack"><img src="{{asset('assets/invoice_asset/img/BG_FD.png')}}"
      style="width: 100%;height: 44.5938px;margin-bottom: 8px;margin-left: 0px;margin-right: 0px">
  </div>
  <div class="container">
    <!-- Header -->
    <div class="header-container">
      <div class="logo-section">
        <img src="{{asset('assets/invoice_asset/img/logo%20big.png')}}" class="logo" alt="Logo">
        <div class="devis-number">Devis N°# {{ $estimate->number }}</div>
        <div class="devis-number">Client N°# {{ $estimate->project->client->ice }}</div>
      </div>

      <div class="date-section">
        <div class="location-date">
          <span class="city" style="font-size: 20px">Salé</span>
          <span style="font-size: 18px">Le {{ $estimate->due_date }}</span>
        </div>
        <div class="client-info" style="align-items: flex-start;">
          <div style="font-size: 18px">{{ $estimate->project->client->name }}</div>
          <br>
          <div style="font-size: 18px">{{ $estimate->project->client->city }}</div>
        </div>
      </div>
    </div>
    <hr>

    <!-- Project Info -->
    {{-- <table>
      <thead>
        <tr>
          <th>Client</th>
          <th>Projet</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{{ $estimate->project->client->name }}</td>
          <td>{{ $estimate->project->name }}</td>
        </tr>
      </tbody>
    </table> --}}

    <!-- Items -->
    <table class="items-table">
      <thead>
        <tr>
          <th>Référence</th>
          <th colspan="2">Désignation</th>
          <th>Qté</th>
          <th>Prix Unite</th>
          <th>Montant</th>
        </tr>
      </thead>
      <tbody>
        @foreach($estimate->items as $item)
        <tr>
          <td>{{ $item->ref }}</td>
          <td colspan="2">{{ $item->name }}</td>
          <td>{{ $item->qte }}</td>
          <td>{{ $item->prix_unite }}</td>
          <td>{{ $item->total_price_unite }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Totals -->
    <table class="totals-table">
      <tr>
        <td class="amount-in-words" rowspan="3">
          Arrêté Le présent devis à La Somme De :<br>
          #... <span>{{ $estimate->total_price_in_words }} DIRHAMS</span> ...#
        </td>
        <td class="totals-column">
          <div class="totals-row">
            <span class="totals-label">Total HT:</span>
            <span>{{ number_format($estimate->total_without_tax, 2) }}</span>
          </div>
        </td>
      </tr>
      <tr>
        <td class="totals-column">
          <div class="totals-row">
            <span class="totals-label">TVA:</span>
            <span>{{ number_format($estimate->tax, 2) }}</span>
          </div>
        </td>
      </tr>
      <tr>
        <td class="totals-column">
          <div class="totals-row">
            <span class="totals-label">Total TTC:</span>
            <span>{{ number_format($estimate->total_with_tax, 2) }}</span>
          </div>
        </td>
      </tr>
    </table>

    <!-- Notes -->
    @if($estimate->note)
    <div style="margin-top: 20px;">
      <h3>Remarques:</h3>
      <p style="font-size: 15px; text-align: center;">{{ $estimate->note }}</p>
    </div>
    @endif

    <!-- Documents -->
    @if($estimate->documents->count() > 0)
    <div style="margin-top: 20px;">
      <h3>Pièces Jointes:</h3>
      <ul>
        @foreach($estimate->documents as $document)
        <li>
          <a href="{{ Storage::url($document->path) }}" target="_blank">
            {{ $document->name }}
          </a>
        </li>
        @endforeach
      </ul>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
      <div class="footer-line"></div>
      <p>
        Adresse : N°97 Rue Assila Laayayda Salé / IF : 3341831 / ICE : 000095738000027/ RC : 16137 CNSS : 8712863<br>
        Patente : 28565292 / Capitale : 100 000,00 Gsm : 06 98 46 33 60 - 06 61 78 99 70<br>
        E-mail : contact@oufoqalbinae.com
      </p>
      <p style="font-style: italic; color: #666;">Merci de Votre Confiance</p>
    </div>
  </div>

  <!-- Print Button -->
  <div class="text-center no-print" style="margin-top: 20px;">
    <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">
      Imprimer
    </button>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const amount = {{ $estimate->total_with_tax }};
      fetch(`/dashboard/order/delivery/${amount}/to-number`)
        .then(response => response.json())
        .then(data => {
          document.querySelector('.amount-in-words span').textContent = data + ' DIRHAMS';
        });
    });
  </script>
</body>

</html>