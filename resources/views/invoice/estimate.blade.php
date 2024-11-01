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
      <h4>Devis N°# {{ $estimate->number }}</h4>
      <div>
        <span style="font-weight: bold; margin-right: 22px;">Salé</span>
        <span>Le {{ $estimate->due_date }}</span>
      </div>
    </div>
    <hr>

    <!-- Project Info -->
    <table>
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
    </table>

    <!-- Items -->
    <table class="items-table">
      <thead>
        <tr>
          <th>Reference</th>
          <th>Quantity</th>
        </tr>
      </thead>
      <tbody>
        @foreach($estimate->items as $item)
        <tr>
          <td>{{ $item->reference }}</td>
          <td>{{ $item->quantity }}</td>
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
          <td style="font-weight: bold;">{{ number_format($estimate->total_price, 2) }}</td>
          <td style="font-weight: bold;">{{ $estimate->tax }}%</td>
          <td style="font-weight: bold;">
            {{ number_format($estimate->total_price + ($estimate->total_price * $estimate->tax / 100), 2) }}
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" style="text-align: center; font-weight: bold; font-size: 18px;">
            Arreté Le présent devis à la somme de :<br>
            #... <span id="numberToWord"></span> ...#
          </td>
        </tr>
      </tfoot>
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
            const totalWithTax = {{ $estimate->total_price + ($estimate->total_price * $estimate->tax / 100) }};
            updateNumberToWord(totalWithTax);
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