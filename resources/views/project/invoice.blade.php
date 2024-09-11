<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Employer invoice - {{$project->ref}}</title>
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }

        th,
        td {
            text-align: center;
            vertical-align: middle;
            font-size: 1rem;
        }
    </style>
</head>

<body class="m-3">
    <div class="invoice-box">
        <div class="container">
            <table cellpadding="0" cellspacing="0">
                <tr class="top">
                    <td colspan="2">
                        <table>
                            <tr>
                                <td class="title">
                                    <img src="{{asset('assets/images/logo-dark.png')}}"
                                        style="width: 100%; max-width: 300px" />
                                </td>

                                <td>
                                    Invoice #: {{$project->ref}}<br />
                                    Created: {{$project->created_at->format('d M Y')}}<br />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="top">
                    <td colspan="2">
                        <table>
                            <tr>
                                <td>
                                    Invoice for Project: {{ $project->name }}<br />
                                    Client: {{ $project->client->name }}<br />
                                </td>

                                <td>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <div class="row">
                <div class="col-md-12 my-2">
                    <h3>Orders </h3>
                    <div class="table-responsive-sm">
                        <table class="table table-bordered table-striped table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ref</th>
                                    <th>Product Count</th>
                                    <th>Total Price</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td> {{ $loop->iteration }}</td>
                                    <td>{{ $order->Ref }}</td>
                                    <td>{{$order->items->count()}}</td>
                                    <td>{{ $order->total_price }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">Total</td>
                                    <td>{{ $orders->sum('total_price') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-md-12 my-2">
                    <h3>Expenses </h3>
                    <div class="table-responsive-sm">
                        <table class="table table-bordered table-striped table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ref</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                <tr>
                                    <td> {{ $loop->iteration }}</td>
                                    <td>{{ $expense->ref }}</td>
                                    <td class="text-center">{{ $expense->name }}</td>
                                    <td>{{ $expense->amount }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">Total</td>
                                    <td>{{ $expenses->sum('amount') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-md-12 text-center my-2">
                    <h3>Total Hours Lost by Employees: {{ $totalHoursLost }} hours</h3>

                    <h3>Total Cost: ${{ $totalCost }}</h3>
                </div>
            </div>
        </div>

    </div>
    <div class="p-2" style="text-align: center; margin: 1rem">
        <button onclick="printInvoice()" type="button" class="btn btn-dark">Print</button>
    </div>
</body>
<script>
    function printInvoice() {
        window.print();
    }
</script>

</html>