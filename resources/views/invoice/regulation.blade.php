<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Régulation - {{ $type === 'supplier' ? 'Fournisseur' : 'Client' }}</title>
  <style>
    body {
      font-family: 'Open Sans', sans-serif;
      background: url("{{asset('assets/invoice_asset/img/Oufoq%20albinae%20BIG.png')}}") center / cover no-repeat;
      min-height: 100vh;
      margin: 0;
      padding: 0;
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
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      text-align: center;
      font-size: 13px;
      padding: 20px;
      background-color: rgba(255, 255, 255, 0.9);
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
        min-height: auto;
      }

      .container {
        background: none;
        margin-bottom: 150px;
      }

      .footer {
        background: none;
        position: fixed;
        bottom: 0;
      }

      .footer-line {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }

      .no-print {
        display: none;
      }

      @page {
        margin: 0;
        size: auto;
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
  <div class="container">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center;">
      <img src="{{asset('assets/invoice_asset/img/logo%20big.png')}}" style="width: 150px;">
      <h4>État de Régulation - {{ $type === 'supplier' ? 'Fournisseur' : 'Client' }}</h4>
      <div>
        <span style="font-weight: bold; margin-right: 22px;">Salé</span>
        <span>Le {{ $date }}</span>
      </div>
    </div>
    <hr>

    <!-- Entity Info -->
    @if($entity)
    <div class="summary-box">
      <h3>{{ $type === 'supplier' ? 'Fournisseur' : 'Client' }}: {{ $entity->name }}</h3>
      <p>ICE: {{ $entity->ice }}</p>
      <p>Adresse: {{ $entity->address }}</p>
      <p>Téléphone: {{ $entity->phone }}</p>
    </div>
    @else
    <h3 class="text-center text-bold">Tous les livraisons pour Les {{ $type === 'supplier' ? 'Fournisseur' : 'Client' }}</h3>
    @endif

    <!-- Deliveries -->
    <table>
      <thead>
        <tr>
          <th>N°</th>
          <th>Project</th>
          <th>Date</th>
          <th>Total TTC</th>
          <th>Payé</th>
          <th>Reste</th>
        </tr>
      </thead>
      <tbody>
        @foreach($deliveries as $delivery)
        <tr>
          <td>{{ $delivery->number }}</td>
          <td>{{ $delivery->project->name }}</td>
          <td>{{ $delivery->date }}</td>
          <td>{{ number_format($delivery->total_with_tax, 2) }}</td>
          <td>{{ number_format($delivery->total_paid, 2) }}</td>
          <td>{{ number_format($delivery->remaining_amount, 2) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Summary -->
    <div>
      <table>
        <tr>
          <th>Total Global</th>
          <th>Total Payé</th>
          <th>Reste à Payer</th>
        </tr>
        <tr>
          <td>{{ number_format($totalAmount, 2) }}</td>
          <td>{{ number_format($totalPaid, 2) }}</td>
          <td>{{ number_format($remainingAmount, 2) }}</td>
        </tr>
      </table>
    </div>

    <!-- Footer -->
    <div class="footer">
      <div class="footer-line"></div>
      <p>
        Adresse : N°97 Rue Assila Laayayda Salé / IF : 3341831 / ICE : 000095738000027/ RC : 16137 CNSS : 8712863<br>
        Patente : 28565292 / Capitale : 100 000,00 Gsm : 06 98 46 33 60 - 06 61 78 99 70<br>
        E-mail : contact@oufoqalbinae.com
      </p>
    </div>
  </div>

  <!-- Print Button -->
  <div class="text-center no-print"
    style="margin-top: 20px; display: flex; justify-content: center; align-items: center;">
    <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">
      Imprimer
    </button>
  </div>
</body>

</html>