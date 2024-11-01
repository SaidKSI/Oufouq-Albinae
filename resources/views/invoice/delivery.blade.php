<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bon de Livraison #{{ $delivery->number }}</title>
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

    .text-center {
      text-align: center;
    }

    .items-table th,
    .items-table td {
      text-align: left;
    }

    .payment-info {
      margin: 20px 0;
      padding: 10px;
      border: 2px solid #000;
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
      <h4>Bon de Livraison N°# {{ $delivery->number }}</h4>
      <div>
        <span style="font-weight: bold; margin-right: 22px;">Salé</span>
        <span>Le {{ $delivery->date }}</span>
      </div>
    </div>
    <hr>

    <!-- Client & Project Info -->
    <table>
      <thead>
        <tr>
          <th>Client</th>
          <th>Projet</th>
          @if($delivery->supplier)
          <th>Fournisseur</th>
          @endif
          <th>Mode de Paiement</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{{ $delivery->client->name }}</td>
          <td>{{ $delivery->project->name }}</td>
          @if($delivery->supplier)
          <td>{{ $delivery->supplier->name }}</td>
          @endif
          <td>{{ ucfirst($delivery->payment_method) }}</td>
        </tr>
      </tbody>
    </table>

    <!-- Items -->
    <table class="items-table">
      <thead>
        <tr>
          <th>Référence</th>
          <th>Désignation</th>
          <th>Catégorie</th>
          <th>Quantité</th>
          <th>Prix Unitaire</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach($delivery->items as $item)
        <tr>
          <td>{{ $item->ref }}</td>
          <td>{{ $item->name }}</td>
          <td>{{ $item->category }}</td>
          <td>{{ $item->qte }}</td>
          <td>{{ number_format($item->prix_unite, 2) }}</td>
          <td>{{ number_format($item->total_price_unite, 2) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Totals -->
    <table>
      <thead>
        <tr>
          <th>Total HT</th>
          <th>TVA</th>
          <th>Total TTC</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="font-weight: bold;">{{ number_format($delivery->total_without_tax, 2) }}</td>
          <td style="font-weight: bold;">{{ $delivery->tax }}%</td>
          <td style="font-weight: bold;">{{ number_format($delivery->total_with_tax, 2) }}</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" style="text-align: center; font-weight: bold; font-size: 18px;">
            Arreté Le présent bon de livraison à la somme de :<br>
            #... <span id="numberToWord"></span> ...#
          </td>
        </tr>
      </tfoot>
    </table>



    <!-- Notes -->
    @if($delivery->note)
    <div style="margin-top: 20px;">
      <h3>Remarques:</h3>
      <p style="font-size: 15px; text-align: center;">{{ $delivery->note }}</p>
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


    <!-- Print Button -->
    <div class="text-center no-print" style="margin-top: 20px;">
      <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">
        Imprimer
      </button>
    </div>
    <!-- Payment Information -->
    @if($delivery->bills->count() > 0)
    <div style="page-break-before: always;">
      <h3>Historique des Paiements</h3>
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Montant</th>
          </tr>
        </thead>
        <tbody>
          @foreach($delivery->bills as $bill)
          <tr>
            <td>{{ $bill->created_at }}</td>
            <td>{{ number_format($bill->amount, 2) }}</td>
          </tr>
          @endforeach
          <tr>
            <td><strong>Total Payé</strong></td>
            <td><strong>{{ number_format($delivery->total_paid, 2) }}</strong></td>
          </tr>
          <tr>
            <td><strong>Reste à Payer</strong></td>
            <td><strong>{{ number_format($delivery->remaining_amount, 2) }}</strong></td>
          </tr>
        </tbody>
      </table>
    </div>
    @endif
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
            updateNumberToWord({{ $delivery->total_with_tax }});
        });

        function updateNumberToWord(amount) {
            fetch(`/dashboard/order/delivery/${amount}/to-number`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('numberToWord').textContent = data;
                })
                .catch(error => console.error('Error:', error));
        }
  </script>
</body>

</html>