<div class="leftside-menu">
  <!-- Brand Logo Light -->
  <a href="index.php" class="logo logo-light">
    <span class="logo-lg">
      <img src="{{ asset('assets/images/logo.png')}}" alt="logo" />
    </span>
    <span class="logo-sm">
      <img src="{{ asset('assets/images/logo-sm.png')}}" alt="small logo" />
    </span>
  </a>

  <!-- Brand Logo Dark -->
  <a href="index.php" class="logo logo-dark">
    <span class="logo-lg">
      <img src="{{ asset('assets/images/logo-dark.png')}}" alt="dark logo" />
    </span>
    <span class="logo-sm">
      <img src="{{ asset('assets/images/logo-sm.png')}}" alt="small logo" />
    </span>
  </a>

  <!-- Sidebar Hover Menu Toggle Button -->
  <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
    <i class="ri-checkbox-blank-circle-line align-middle"></i>
  </div>

  <!-- Full Sidebar Menu Close Button -->
  <div class="button-close-fullsidebar">
    <i class="ri-close-fill align-middle"></i>
  </div>

  <!-- Sidebar -left -->
  <div class="h-100" id="leftside-menu-container" data-simplebar>
    <!--- Sidemenu -->
    <ul class="side-nav">
      <li class="side-nav-title">Navigation</li>

      <li class="side-nav-item">
        <a href="{{route('dashboard.index')}}" class="side-nav-link">
          <i class="ri-home-4-line"></i>
          <span> Tableaux de bord </span>
        </a>
      </li>
      <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sidebarTasks" aria-expanded="false" aria-controls="sidebarTasks"
          class="side-nav-link">
          <i class="bi bi-people-fill"></i>
          <span> Fornaisseur </span>
          <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarTasks">
          <ul class="side-nav-second-level">
            <li>
              <a href="{{route('supplier.index')}}">Suppliers</a>
            </li>
            <li>
              <a href="{{route('product.index')}}">Product</a>
            </li>
            <li>
              <a href="{{route('purchase')}}">Les Achats</a>
            </li>
            <li>
              <a href="{{route('dashboard.maintenance')}}">Relegement</a>
            </li>
            <li>
              <a href="{{route('dashboard.maintenance')}}">Etat</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sidebarClients" aria-expanded="false" aria-controls="sidebarClients"
          class="side-nav-link">
          <i class="bi bi-people-fill"></i>
          <span> Clients </span>
          <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarClients">
          <ul class="side-nav-second-level">
            <li>
              <a href="{{route('client.index')}}">Client</a>
            </li>
            <li>
              <a href="{{route('project.index')}}">Projet</a>
            </li>
            <li>
              <a href="{{route('dashboard.maintenance')}}">Les Charge de Projet</a>
            </li>
            <li>
              <a href="{{route('dashboard.maintenance')}}">Travaux</a>
            </li>
            <li>
              <a href="{{route('dashboard.maintenance')}}">Regelement</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="side-nav-item">
        <a href="{{route('stock')}}" class="side-nav-link">
          <i class="bi bi-boxes"></i>
          <span> Stock </span>
        </a>
      </li>
      <li class="side-nav-item">
        <a href="{{route('dashboard.maintenance')}}" class="side-nav-link">
          <i class="bi bi-file-earmark-text-fill"></i>
          <span> Devis </span>
        </a>
      </li>
      <li class="side-nav-item">
        <a href="{{route('dashboard.maintenance')}}" class="side-nav-link">
          <i class="bi bi-file-earmark-text-fill"></i>
          <span> Facteur </span>
        </a>
      </li>
      <li class="side-nav-item">
        <a href="{{route('order.index')}}" class="side-nav-link">
          <i class="bi bi-file-earmark-text-fill"></i>
          <span> Bon de Commande </span>
        </a>
      </li>
      <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sidebarCharges" aria-expanded="false" aria-controls="sidebarCharges"
          class="side-nav-link">
          <i class="bi bi-clipboard-check "></i>
          <span> Les Charges </span>
          <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarCharges">
          <ul class="side-nav-second-level">

            <li>
              <a href="{{route('expenses.index')}}">Les Charges</a>
            </li>
            <li>
              <a href="{{route('expenses.transportation')}}">Transportation</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sidebarMultiLevel" aria-expanded="false" aria-controls="sidebarMultiLevel"
          class="side-nav-link">
          <i class="bi bi-person-badge"></i>
          <span> Personnel </span>
          <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarMultiLevel">
          <ul class="side-nav-second-level">
            <li class="side-nav-item">
              <a data-bs-toggle="collapse" href="#personnel" aria-expanded="false" aria-controls="personnel">

                <span><i class="ri-map-pin-user-fill"></i> Employee </span>
                <span class="menu-arrow"></span>
              </a>
              <div class="collapse" id="personnel">
                <ul class="side-nav-third-level">
                  <li>
                    <a href="{{route('employee.index')}}">Employees</a>
                  </li>
                  <li>
                    <a href="{{route('employee.payment')}}">Payment</a>
                  </li>
                  <li>
                    <a href="{{route('dashboard.maintenance')}}">Absense</a>
                  </li>
                  <li>
                    <a href="{{route('dashboard.maintenance')}}">Regelement</a>
                  </li>

                  <li>
                    <a href="{{route('profession')}}">Employees Profession</a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="side-nav-item">
              <a data-bs-toggle="collapse" href="#shift" aria-expanded="false" aria-controls="shift">

                <span><i class="ri-time-fill"></i> Shift </span>
                <span class="menu-arrow"></span>
              </a>
              <div class="collapse" id="shift">
                <ul class="side-nav-third-level">
                  <li>
                    <a href="{{route('shift.index')}}">Shifts</a>
                  </li>
                  <li>
                    <a href="{{route('shift.attendance')}}">Attendance</a>
                  </li>
                  <li>
                    <a href="{{route('shift.overview')}}">OverView</a>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </li>

      <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sidebarDeveloper" aria-expanded="false" aria-controls="sidebarDeveloper"
          class="side-nav-link">
          <i class="ri-task-line"></i>
          <span> Developer </span>
          <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarDeveloper">
          <ul class="side-nav-second-level">
            <li>
              <a href="{{route('dashboard.maintenance')}}">Les point a regles</a>
            </li>
            <li>
              <a href="{{route('task.index')}}">Les Taches</a>
            </li>
          </ul>
        </div>
      </li>
    </ul>
    <div class="clearfix"></div>
  </div>
</div>