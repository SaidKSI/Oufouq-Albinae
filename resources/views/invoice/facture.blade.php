<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->number }}</title>
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

        @media print {
            body {
                background: none;
            }

            .container {
                background: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <img src="{{asset('assets/invoice_asset/img/logo%20big.png')}}" style="width: 150px;">
            <h4>Facture N°# {{ $invoice->number }}</h4>
            <div>
                <span style="font-weight: bold; margin-right: 22px;">Salé</span>
                <span>Le {{ $invoice->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Projet</th>
                    <th>Payment Method</th>
                    <th>Transaction ID</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->estimate->project->client->name }}</td>
                    <td>{{ $invoice->estimate->project->name }}</td>
                    <td>{{ $invoice->payment_method }}</td>
                    <td>{{ $invoice->transaction_id }}</td>
                </tr>
            </tbody>
        </table>
        <table style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>Total HT</th>
                    <th>TVA</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight: bold;">{{ $invoice->total_without_tax }}</td>
                    <td style="font-weight: bold;">{{ $invoice->tax }}%</td>
                    <td style="font-weight: bold;">{{ number_format($invoice->total_with_tax, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="text-center">
                    <td colspan="5" style="text-align: center;font-weight: bold;font-size: 18px;">
                        Arreté La présente facture à la somme de :<br>
                        #... <span id="numberToWord"></span> ...#
                    </td>
                </tr>
            </tfoot>
        </table>
        @if($invoice->note)
        <div style="margin-top: 20px;">
            <h3>Remarques:</h3>
            <p style="font-size: 15px;text-align: center;">{{ $invoice->note }}</p>
        </div>
        @endif
        <div class="footer">
            <div class="footer-line"></div>
            <p>
                Adresse : N°97 Rue Assila Laayayda Salé / IF : 3341831 / ICE : 000095738000027/ RC : 16137 CNSS :
                8712863<br>
                Patente : 28565292 / Capitale : 100 000,00 Gsm : 06 98 46 33 60 - 06 61 78 99 70<br>
                E-mail : contact@oufoqalbinae.com
            </p>
            <p style="font-style: italic; color: #666;">Merci de Votre Confiance</p>
        </div>
    </div>
    <div class="text-center no-print" style="margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">
            Imprimer
        </button>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            updateNumberToWord({{ $invoice->total_with_tax }});
        });

        function updateNumberToWord(totalWithTax) {
            fetch(`/dashboard/order/delivery/${totalWithTax}/to-number`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('numberToWord').textContent = data;
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>