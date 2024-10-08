<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>Oufoq Albinae</title>
  <link rel="icon" type="image/png" sizes="118x118" href="/assets/invoice_asset/img/Circel%20logo.png">
  <link rel="icon" type="image/png" sizes="118x118" href="/assets/invoice_asset/img/Circel%20logo.png">
  <link rel="icon" type="image/png" sizes="118x118" href="/assets/invoice_asset/img/Circel%20logo.png"
    media="(prefers-color-scheme: dark)">
  <link rel="icon" type="image/png" sizes="118x118" href="/assets/invoice_asset/img/Circel%20logo.png">
  <link rel="icon" type="image/png" sizes="118x118" href="/assets/invoice_asset/img/Circel%20logo.png">
  <link rel="icon" type="image/png" sizes="118x118" href="/assets/invoice_asset/img/Circel%20logo.png">
  <link rel="stylesheet" href="/assets/invoice_asset/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&amp;display=swap">
  <link rel="stylesheet" href="/assets/invoice_asset/css/Login-Form-Basic-icons.css">
  <style>
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      padding-top: 120px;
      /* Adjust this value based on your navbar height */
    }

    .content {
      flex: 1 0 auto;
    }

    .footer {
      flex-shrink: 0;
    }

    .navbar-fixed-top {
      position: fixed;
      top: 0;
      right: 0;
      left: 0;
      z-index: 1030;
      background-color: white;
    }
  </style>
</head>

<body>

  <div class="content">
    <div class="container-fluid"
      style="background: url(&quot;/assets/invoice_asset/img/Oufoq%20albinae%20BIG.png&quot;) center / cover no-repeat;">
      <nav class="navbar fixed-top">
        <div class="container-fluid">
          <div class="vstack"><img src="/assets/invoice_asset/img/BG_FD.png"
              style="width: 100%;height: 44.5938px;margin-bottom: 8px;">
            <div class="row d-flex d-xl-flex align-items-center align-items-xl-center">
              <div class="col"><img src="/assets/invoice_asset/img/logo%20big.png"
                  style="width: 150px;background: rgba(255,255,255,0);"></div>
              <div class="col">
                <h4 class="text-capitalize text-center">Facture N°#{{$estimate->number}}</h4>
              </div>
              <div class="col text-center"><span class="fw-bold" style="margin-right: 22px;">Salé</span><span>Le
                  {{$estimate->created_at->format('d/m/Y')}}</span></div>
            </div>
          </div>
        </div>
      </nav>

      <div style="margin-top: 210px;">
        <table class="table table-sm table-borderless">
          <thead>
            <tr class="text-uppercase text-start">
              <th
                style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0);border-bottom-style: none;width: 105px;">
                N° Client</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr class="text-uppercase text-start">
              <td style="background: rgba(255,255,255,0);border: 2px solid rgb(0,0,0) ;border-top-style: none;">
                00{{$estimate->project->client_id}}</td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="text-center" style="margin-bottom: 20px;">
        <table class="table table-sm table-borderless">
          <thead>
            <tr class="text-uppercase">
              <th class="border-2 border-dark">Référence</th>
              <th class="border-2 border-dark">Désignation</th>
              <th class="border-2 border-dark">Qté</th>
              <th class="border-2 border-dark">Prix unitaire</th>
              <th class="border-2 border-dark">montant</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th class="border-2 border-dark" style="background: rgba(255,255,255,0);height: 400px;">
                {{$estimate->reference}}</th>
              <th class="border-2 border-dark" style="background: rgba(255,255,255,0);height: 400px;">
                {{$estimate->project->name}}</th>
              <th class="border-2 border-dark" style="background: rgba(255,255,255,0);height: 400px;">
                {{$estimate->quantity}}</th>
              <th class="border-2 border-dark" style="background: rgba(255,255,255,0);height: 400px;">
                {{number_format($estimate->total_price, 2)}}</th>
              <th class="border-2 border-dark" style="background: rgba(255,255,255,0);height: 400px;">{{
                number_format($total,2)}}</th>
            </tr>
            <tr>
              <th class="text-capitalize border-2 border-dark" style="background: rgba(255,255,255,0);" colspan="2"
                rowspan="2">Arreté La présente facture à la somme de :<br>#...
                <span class="fs-6">{{$total_in_alphabetic}}</span> Dirhams...#
              </th>
              <th class="text-uppercase border-2 border-dark" style="background: rgba(255,255,255,0);">
                total
                ht</th>
              <th class="text-uppercase border-2 border-dark" style="background: rgba(255,255,255,0);">tva
              </th>
              <th class="text-uppercase border-2 border-dark" style="background: rgba(255,255,255,0);">
                total
              </th>
            </tr>
            <tr>

              <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">{{
                number_format($totalWithoutTax, 2) }}</th>
              <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">{{
                number_format($taxAmount, 2) }}</th>
              <th class="border-2 border-dark" style="background: rgba(255,255,255,0);">{{
                number_format($estimate->total_price + $taxAmount, 2) }}</th>
            </tr>
          </tbody>
        </table>
      </div>


    </div>
  </div>

  <footer class="footer">
    <nav class="navbar" style="margin-bottom: -60px;">
      <div class="container-fluid">
        <div class="vstack w-100">
          <div style="width: 100%;height: 3px;background: #ed961c;border-radius: 26px;margin-bottom: 5px;">
          </div>
          <p class="fw-bold text-center" style="font-size: 13px;">Adresse : {{$company->address}} / IF :
            {{$company->if}} / ICE :&nbsp; {{$company->ice}}/ RC : {{$company->ice}} CNSS :
            {{$company->cnss}}&nbsp;<br>Patente : {{$company->patente}} / Capitale : {{
            number_format($company->capital, 2) }} Gsm : {{$company->phone1}} -
            {{$company->phone2}}<br>E-mail :&nbsp;{{$company->email}}</p>
          <p class="text-capitalize text-center text-muted">Merci de Votre Confiance</p>
        </div>
      </div>
    </nav>
  </footer>

  <script src="/assets/invoice_asset/js/jquery.min.js"></script>
  <script src="/assets/invoice_asset/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>