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
      padding: 20px;
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

    .summary-box {
      border: 2px solid #000;
      padding: 15px;
      margin: 20px 0;
      border-radius: 8px;
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
  <div class="text-center no-print" style="margin-top: 20px; display: flex; justify-content: center; align-items: center;">
    <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">
      Imprimer
    </button>
  </div>
</body>

</html>