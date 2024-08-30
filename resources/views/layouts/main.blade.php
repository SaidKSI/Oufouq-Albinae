<!DOCTYPE html>
<html lang="en">

<head>
  <title>Log In | Attex - Bootstrap 5 Admin & Dashboard Template</title>
  <meta charset="utf-8" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
  <meta content="Coderthemes" name="author" />

  <!-- App favicon -->
  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
  <!-- Theme Config Js -->
  <script src="{{ asset('assets/js/config.js') }}"></script>

  <!-- App css -->
  <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

  <!-- Icons css -->
  <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/vendor/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
  @stack('styles')
</head>
<style>
  .select2-container--default .select2-selection--multiple .select2-selection__choice {
    color: #0d6efd;
    /* Bootstrap primary color */
  }

  th,
  td {
    text-align: center;
    vertical-align: middle;
    font-size: 1rem;
  }

  .pointer {
    cursor: pointer;
  }
</style>
<!-- Pre-loader -->
<div id="preloader">
  <div id="status">
    <div class="bouncing-loader">
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>
</div>
<!-- End Preloader-->
@yield('main-content')
<!-- Vendor js -->
<script src="{{ asset('assets/js/vendor.min.js') }}"></script>
<!-- Plugins js -->
<script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>

<!-- App js -->
<script src="{{ asset('assets/js/app.min.js') }}"></script>
@stack('scripts')
</body>

</html>