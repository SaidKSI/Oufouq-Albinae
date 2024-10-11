<div class="navbar-custom">
  <div class="topbar container-fluid">
    <div class="d-flex align-items-center gap-lg-2 gap-1">


      <div class="logo-topbar">

        <a href="{{route('dashboard.index')}}" class="logo-light">
          <span class="logo-lg">
            <img src="{{ asset('assets/images/logo.png') }}" alt="logo">
          </span>
          <span class="logo-sm">
            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo">
          </span>
        </a>


        <a href="{{route('dashboard.index')}}" class="logo-dark">
          <span class="logo-lg">
            <img src="{{ asset('assets/images/logo-dark.png') }}" alt="dark logo">
          </span>
          <span class="logo-sm">
            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo">
          </span>
        </a>
      </div>


      <button class="button-toggle-menu">
        <i class="ri-menu-2-fill"></i>
      </button>


      <button class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
        <div class="lines">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </button>


      <div class="app-search dropdown d-none d-lg-block">
        <x-company-capital />
      </div>
    </div>

    <ul class="topbar-menu d-flex align-items-center gap-3">
      <li class="dropdown d-lg-none">
        <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button"
          aria-haspopup="false" aria-expanded="false">
          <i class="ri-search-line fs-22"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
          <form class="p-3">
            <input type="search" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
          </form>
        </div>
      </li>




      <li class="d-none d-sm-inline-block">
        <div class="nav-link" id="light-dark-mode" data-bs-toggle="tooltip" data-bs-placement="left" title="Theme Mode">
          <i class="ri-moon-line fs-22"></i>
        </div>
      </li>


      <li class="d-none d-md-inline-block">
        <a class="nav-link" href="" data-toggle="fullscreen">
          <i class="ri-fullscreen-line fs-22"></i>
        </a>
      </li>

      <li class="dropdown">
        <a class="nav-link dropdown-toggle arrow-none nav-user px-2" data-bs-toggle="dropdown" href="#" role="button"
          aria-haspopup="false" aria-expanded="false">
          <span class="account-user-avatar">
            <img src="{{ asset('assets/images/users/avatar-1.png') }}" alt="user-image" width="32"
              class="rounded-circle">
          </span>
          <span class="d-lg-flex flex-column gap-1 d-none">
            <h5 class="my-0">{{auth()->user()->user_name}}</h5>
            <h6 class="my-0 fw-normal">{{auth()->user()->is_supervisor ? 'Supervisor' : 'Admin'}} </h6>
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">

          <div class=" dropdown-header noti-title">
            <h6 class="text-overflow m-0">Welcome !</h6>
          </div>



          <form id="logout-form" action="" method="POST" style="display: none;">
            @csrf
          </form>

          <a href="#" class="dropdown-item"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="ri-logout-box-line fs-18 align-middle me-1"></i>
            <span>Logout</span>
          </a>
        </div>
      </li>
    </ul>
  </div>
</div>