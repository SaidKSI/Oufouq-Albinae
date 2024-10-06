@extends('layouts.main')
@section('title', 'Login')
@section('main-content')

<body class="authentication-bg position-relative">

  @include('partials._background')
  <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xxl-4 col-lg-5">
          <div class="card">

            <!-- Logo -->
            <div class="card-header py-4 text-center bg-primary">
              <a href="index.php">
            <span><img src="{{ asset('assets/images/logo.png') }}" alt="logo" height="22"></span>
              </a>
            </div>

            <div class="card-body p-4">

              <div class="text-center w-75 m-auto">
                <h4 class="text-dark-50 text-center pb-0 fw-bold">Sign In</h4>
                <p class="text-muted mb-4">Enter your email address and password to access admin panel.</p>
              </div>

              <form action="{{ route('submit.login') }}" method="POST">
                @csrf
                <div class="mb-3">
                  <label for="emailaddress" class="form-label">Email address</label>
                  <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email"
                    id="emailaddress" required="" placeholder="Enter your email" value="{{ old('email') }}">
                  @if ($errors->has('email'))
                  <div class="invalid-feedback">
                    {{ $errors->first('email') }}
                  </div>
                  @endif
                </div>

                <div class="mb-3">
                  <a href="auth-recoverpw.php" class="text-muted float-end fs-12">Forgot your password?</a>
                  <label for="password" class="form-label">Password</label>
                  <div class="input-group input-group-merge">
                    <input type="password" name="password" id="password" class="form-control"
                      placeholder="Enter your password">
                    <div class="input-group-text" data-password="false">
                      <span class="password-eye"></span>
                    </div>
                  </div>
                  <div class="invalid-tooltip">
                    Please enter last name.
                  </div>
                </div>

                <div class="mb-3 mb-3">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="checkbox-signin" checked>
                    <label class="form-check-label" for="checkbox-signin">Remember me</label>
                  </div>
                </div>

                <div class="mb-3 mb-0 text-center">
                  <button class="btn btn-primary" type="submit" type="submit"> Log In </button>
                </div>

              </form>
            </div> <!-- end card-body -->
          </div>
          <!-- end card -->
          <!-- end row -->

        </div> <!-- end col -->
      </div>
      <!-- end row -->
    </div>
    <!-- end container -->
  </div>
  <!-- end page -->

 
</body>
@endsection