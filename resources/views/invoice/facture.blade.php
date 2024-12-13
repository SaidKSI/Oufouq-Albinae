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
    <div class="vstack"><img src="{{asset('assets/invoice_asset/img/BG_FD.png')}}"
            style="width: 100%;height: 44.5938px;margin-bottom: 8px;margin-left: 0px;margin-right: 0px">
    </div>
    <div class="container">
        <!-- Header -->
        <div class="header-container">
            <div class="logo-section">
                <img src="{{asset('assets/invoice_asset/img/logo%20big.png')}}" class="logo" alt="Logo">
                <div class="devis-number">Facture N°# {{ $invoice->number }}</div>
                <div class="devis-number">
                    Client N°#
                    @if($invoice->estimate_id)
                    {{ $invoice->estimate->project->client->ice }}
                    @else
                    {{ $invoice->delivery->client->ice }}
                    @endif
                </div>
            </div>

            <div class="date-section">
                <div class="location-date">
                    <span class="city" style="font-size: 20px">Salé</span>
                    <span style="font-size: 18px">Le {{ $invoice->date->format('Y-m-d') }}</span>
                </div>
                <div class="client-info" style="align-items: flex-start;">
                    <div style="font-size: 18px">
                        @if($invoice->estimate_id)
                        {{ $invoice->estimate->project->client->name }}
                        @else
                        {{ $invoice->delivery->client->name }}
                        @endif
                    </div>
                    <br>
                    <div style="font-size: 18px">
                        @if($invoice->estimate_id)
                        {{ $invoice->estimate->project->client->city }}
                        @else
                        {{ $invoice->delivery->client->city }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Désignation</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @if($invoice->estimate_id)
                @foreach($invoice->estimate->items as $item)
                <tr>
                    <td>{{ $item->ref }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->qte }}</td>
                    <td>{{ number_format($item->prix_unite, 2) }}</td>
                    <td>{{ number_format($item->total_price_unite, 2) }}</td>
                </tr>
                @endforeach
                @else
                @foreach($invoice->delivery->items as $item)
                <tr>
                    <td>{{ $item->ref }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->qte }}</td>
                    <td>{{ number_format($item->prix_unite, 2) }}</td>
                    <td>{{ number_format($item->total_price_unite, 2) }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td class="amount-in-words" rowspan="3">
                    Arrêté Le présent devis à La Somme De :<br>
                    #... <span id="numberToWord">{{ $invoice->total_price_in_words }} DIRHAMS</span> ...#
                </td>
                <td class="totals-column">
                    <div class="totals-row">
                        <span class="totals-label">Total HT:</span>
                        <span>{{ number_format($invoice->total_without_tax, 2) }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="totals-column">
                    <div class="totals-row">
                        <span class="totals-label">TVA:</span>
                        <span>{{ number_format($invoice->tax, 2) }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="totals-column">
                    <div class="totals-row">
                        <span class="totals-label">Total TTC:</span>
                        <span>{{ number_format($invoice->total_with_tax, 2) }}</span>
                    </div>
                </td>
            </tr>
        </table>
        <table style="width: 50%; margin: 0 auto;font-size: 12px;font-weight: normal;">
            <thead>
                <tr>
                    <th>Payment Method</th>
                    @if($invoice->payment_method != 'cash')
                    <th>Transaction ID</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->payment_method }}</td>
                    @if($invoice->payment_method != 'cash')
                    <td>{{ $invoice->transaction_id }}</td>
                    @endif
                </tr>
            </tbody>
        </table>
        <x-invoice-footer />
    </div>

    <div class="text-center no-print" style="margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">
            Imprimer
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amount = {{ $invoice->total_with_tax }};
            fetch(`/dashboard/order/delivery/${amount}/to-number`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('numberToWord').textContent = data + ' DIRHAMS';
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>

</html>