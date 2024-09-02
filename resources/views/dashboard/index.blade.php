@extends('layouts.app')

@section('content')
<x-Breadcrumb title="Dashboard" />
<div class="row row-cols-1 row-cols-xxl-5 row-cols-lg-3 row-cols-md-2">
  <div class="col">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="flex-grow-1 overflow-hidden">
            <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Chiffre D'affaire</h5>
            <h3 class="my-3">54,214</h3>
            <p class="mb-0 mx-0 text-muted text-truncate">
              <span>Dêbit</span>
              <span class="badge bg-success"><i class="ri-arrow-up-line"></i> 2,541</span>
              <span>Credit</span>
              <span class="badge bg-danger"><i class="ri-arrow-down-line"></i> 3,532</span>
            </p>
          </div>
          <div class="flex-shrink-0">
            <div id="widget-customers" class="apex-charts" data-colors="#47ad77,#e3e9ee"></div>
          </div>
        </div>
      </div> <!-- end card-body-->
    </div> <!-- end card-->
  </div> <!-- end col-->

  <div class="col">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="flex-grow-1 overflow-hidden">
            <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Commandes</h5>
            <h3 class="my-3">7,543</h3>
            <p class="mb-0 text-muted text-truncate">
              <span class="badge bg-danger me-1"><i class="ri-arrow-down-line"></i> 1.08%</span>
              <span>Since last month</span>
            </p>
          </div>
          <div id="widget-orders" class="apex-charts" data-colors="#3e60d5,#e3e9ee"></div>
        </div>
      </div> <!-- end card-body-->
    </div> <!-- end card-->
  </div> <!-- end col-->

  <div class="col">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="flex-grow-1 overflow-hidden">
            <h5 class="text-muted fw-normal mt-0" title="Average Revenue">Revenu</h5>
            <h3 class="my-3">$9,254</h3>
            <p class="mb-0 text-muted text-truncate">
              <span class="badge bg-danger me-1"><i class="ri-arrow-down-line"></i> 7.00%</span>
              <span>Since last month</span>
            </p>
          </div>
          <div id="widget-revenue" class="apex-charts" data-colors="#16a7e9,#e3e9ee"></div>
        </div>

      </div> <!-- end card-body-->
    </div> <!-- end card-->
  </div> <!-- end col-->

  <div class="col col-lg-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="flex-grow-1 overflow-hidden">
            <h5 class="text-muted fw-normal mt-0" title="Growth">Growth</h5>
            <h3 class="my-3">+ 20.6%</h3>
            <p class="mb-0 text-muted text-truncate">
              <span class="badge bg-success me-1"><i class="ri-arrow-up-line"></i> 4.87%</span>
              <span>Since last month</span>
            </p>
          </div>
          <div id="widget-growth" class="apex-charts" data-colors="#ffc35a,#e3e9ee"></div>
        </div>

      </div> <!-- end card-body-->
    </div> <!-- end card-->
  </div> <!-- end col-->
  <div class="col col-lg-6 col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="flex-grow-1 overflow-hidden">
            <h5 class="text-muted fw-normal mt-0" title="Conversation Ration">Conversation</h5>
            <h3 class="my-3">9.62%</h3>
            <p class="mb-0 text-muted text-truncate">
              <span class="badge bg-success me-1"><i class="ri-arrow-up-line"></i> 3.07%</span>
              <span>Since last month</span>
            </p>
          </div>
          <div id="widget-conversation" class="apex-charts" data-colors="#f15776,#e3e9ee"></div>
        </div>

      </div> <!-- end card-body-->
    </div> <!-- end card-->
  </div> <!-- end col-->
</div>
<div class="row">
  <div class="col-lg-6">
    <div class="card">
      <div class="d-flex card-header justify-content-between align-items-center">
        <h4 class="header-title">Revenue</h4>
        <div class="dropdown">
          <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="ri-more-2-fill"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
            <!-- item-->
            <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
            <!-- item-->
            <a href="javascript:void(0);" class="dropdown-item">Profit</a>
            <!-- item-->
            <a href="javascript:void(0);" class="dropdown-item">Action</a>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="bg-light-subtle border-top border-bottom border-light">
          <div class="row text-center">
            <div class="col">
              <p class="text-muted mt-3"><i class="ri-donut-chart-fill"></i> Current Week</p>
              <h3 class="fw-normal mb-3">
                <span>$1705.54</span>
              </h3>
            </div>
            <div class="col">
              <p class="text-muted mt-3"><i class="ri-donut-chart-fill"></i> Previous Week</p>
              <h3 class="fw-normal mb-3">
                <span>$6,523.25 <i class="ri-corner-right-up-fill text-success"></i></span>
              </h3>
            </div>
            <div class="col">
              <p class="text-muted mt-3"><i class="ri-donut-chart-fill"></i> Conversation</p>
              <h3 class="fw-normal mb-3">
                <span>8.27%</span>
              </h3>
            </div>
            <div class="col">
              <p class="text-muted mt-3"><i class="ri-donut-chart-fill"></i> Customers</p>
              <h3 class="fw-normal mb-3">
                <span>69k <i class="ri-corner-right-down-line text-danger"></i></span>
              </h3>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body pt-0">
        <div dir="ltr">
          <div id="revenue-chart" class="apex-charts mt-3" data-colors="#3e60d5,#47ad77"></div>
        </div>
      </div> <!-- end card-body-->
    </div> <!-- end card-->
  </div>
  <div class="col-lg-6">
    <div class="mt-2">
      <h5 class="m-0 pb-2">
        <a class="text-dark" data-bs-toggle="collapse" href="#todayTasks" role="button" aria-expanded="false"
          aria-controls="todayTasks">
          <i class='ri-arrow-down-s-line fs-18'></i>Aujourd'hui <span class="text-muted">(10)</span>
        </a>
      </h5>

      <div class="collapse show" id="todayTasks">
        <div class="card mb-0">
          <div class="card-body">
            <!-- task -->
            <div class="row justify-content-sm-between">
              <div class="col-sm-6 mb-2 mb-sm-0">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="task1">
                  <label class="form-check-label" for="task1">
                    Draft the new contract document for sales team
                  </label>
                </div> <!-- end checkbox -->
              </div> <!-- end col -->
              <div class="col-sm-6">
                <div class="d-flex justify-content-between">
                  <div id="tooltip-container">
                  </div>
                  <div>
                    <ul class="list-inline fs-13 text-end">
                      <li class="list-inline-item">
                        <i class='ri-calendar-todo-line fs-16 me-1'></i> Today 10am
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-list-check-3 fs-16 me-1'></i> 3/7
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-chat-2-line fs-16 me-1'></i> 21
                      </li>
                      <li class="list-inline-item ms-2">
                        <span class="badge bg-danger-subtle text-danger p-1">High</span>
                      </li>
                    </ul>
                  </div>
                </div> <!-- end .d-flex-->
              </div> <!-- end col -->
            </div>
            <!-- end task -->

            <!-- task -->
            <div class="row justify-content-sm-between mt-2">
              <div class="col-sm-6 mb-2 mb-sm-0">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="task2">
                  <label class="form-check-label" for="task2">
                    iOS App home page
                  </label>
                </div> <!-- end checkbox -->
              </div> <!-- end col -->
              <div class="col-sm-6">
                <div class="d-flex justify-content-between">
                  <div id="tooltip-container1">

                  </div>
                  <div>
                    <ul class="list-inline fs-13 text-end">
                      <li class="list-inline-item">
                        <i class='ri-calendar-todo-line fs-16 me-1'></i> Today 4pm
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-list-check-3 fs-16 me-1'></i> 2/7
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-chat-2-line fs-16 me-1'></i> 48
                      </li>
                      <li class="list-inline-item ms-2">
                        <span class="badge bg-danger-subtle text-danger p-1">High</span>
                      </li>
                    </ul>
                  </div>
                </div> <!-- end .d-flex-->
              </div> <!-- end col -->
            </div>
            <!-- end task -->

            <!-- task -->
            <div class="row justify-content-sm-between mt-2">
              <div class="col-sm-6 mb-2 mb-sm-0">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="task3">
                  <label class="form-check-label" for="task3">
                    Write a release note
                  </label>
                </div> <!-- end checkbox -->
              </div> <!-- end col -->
              <div class="col-sm-6">
                <div class="d-flex justify-content-between">
                  <div id="tooltip-container2">

                  </div>
                  <div>
                    <ul class="list-inline fs-13 text-end mb-0">
                      <li class="list-inline-item">
                        <i class='ri-calendar-todo-line fs-16 me-1'></i> Today 6pm
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-list-check-3 fs-16 me-1'></i> 18/21
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-chat-2-line fs-16 me-1'></i> 73
                      </li>
                      <li class="list-inline-item ms-2">
                        <span class="badge bg-info-subtle text-info p-1">Medium</span>
                      </li>
                    </ul>
                  </div>
                </div> <!-- end .d-flex-->
              </div> <!-- end col -->
            </div>
            <!-- end task -->
            <!-- task -->
            <div class="row justify-content-sm-between mt-2">
              <div class="col-sm-6 mb-2 mb-sm-0">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="task3">
                  <label class="form-check-label" for="task3">
                    Write a release note
                  </label>
                </div> <!-- end checkbox -->
              </div> <!-- end col -->
              <div class="col-sm-6">
                <div class="d-flex justify-content-between">
                  <div id="tooltip-container2">

                  </div>
                  <div>
                    <ul class="list-inline fs-13 text-end mb-0">
                      <li class="list-inline-item">
                        <i class='ri-calendar-todo-line fs-16 me-1'></i> Aujourd'hui 6pm
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-list-check-3 fs-16 me-1'></i> 18/21
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-chat-2-line fs-16 me-1'></i> 73
                      </li>
                      <li class="list-inline-item ms-2">
                        <span class="badge bg-info-subtle text-info p-1">Medium</span>
                      </li>
                    </ul>
                  </div>
                </div> <!-- end .d-flex-->
              </div> <!-- end col -->
            </div>
            <!-- end task -->
            <!-- task -->
            <div class="row justify-content-sm-between mt-2">
              <div class="col-sm-6 mb-2 mb-sm-0">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="task3">
                  <label class="form-check-label" for="task3">
                    Write a release note
                  </label>
                </div> <!-- end checkbox -->
              </div> <!-- end col -->
              <div class="col-sm-6">
                <div class="d-flex justify-content-between">
                  <div id="tooltip-container2">

                  </div>
                  <div>
                    <ul class="list-inline fs-13 text-end mb-0">
                      <li class="list-inline-item">
                        <i class='ri-calendar-todo-line fs-16 me-1'></i> Aujourd'hui 6pm
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-list-check-3 fs-16 me-1'></i> 18/21
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-chat-2-line fs-16 me-1'></i> 73
                      </li>
                      <li class="list-inline-item ms-2">
                        <span class="badge bg-info-subtle text-info p-1">Medium</span>
                      </li>
                    </ul>
                  </div>
                </div> <!-- end .d-flex-->
              </div> <!-- end col -->
            </div>
            <!-- end task -->
            <!-- task -->
            <div class="row justify-content-sm-between mt-2">
              <div class="col-sm-6 mb-2 mb-sm-0">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="task3">
                  <label class="form-check-label" for="task3">
                    Write a release note
                  </label>
                </div> <!-- end checkbox -->
              </div> <!-- end col -->
              <div class="col-sm-6">
                <div class="d-flex justify-content-between">
                  <div id="tooltip-container2">

                  </div>
                  <div>
                    <ul class="list-inline fs-13 text-end mb-0">
                      <li class="list-inline-item">
                        <i class='ri-calendar-todo-line fs-16 me-1'></i> Aujourd'hui 6pm
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-list-check-3 fs-16 me-1'></i> 18/21
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-chat-2-line fs-16 me-1'></i> 73
                      </li>
                      <li class="list-inline-item ms-2">
                        <span class="badge bg-info-subtle text-info p-1">Medium</span>
                      </li>
                    </ul>
                  </div>
                </div> <!-- end .d-flex-->
              </div> <!-- end col -->
            </div>
            <!-- end task -->
            <!-- task -->
            <div class="row justify-content-sm-between mt-2">
              <div class="col-sm-6 mb-2 mb-sm-0">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="task3">
                  <label class="form-check-label" for="task3">
                    Write a release note
                  </label>
                </div> <!-- end checkbox -->
              </div> <!-- end col -->
              <div class="col-sm-6">
                <div class="d-flex justify-content-between">
                  <div id="tooltip-container2">

                  </div>
                  <div>
                    <ul class="list-inline fs-13 text-end mb-0">
                      <li class="list-inline-item">
                        <i class='ri-calendar-todo-line fs-16 me-1'></i> Aujourd'hui 6pm
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-list-check-3 fs-16 me-1'></i> 18/21
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-chat-2-line fs-16 me-1'></i> 73
                      </li>
                      <li class="list-inline-item ms-2">
                        <span class="badge bg-info-subtle text-info p-1">Medium</span>
                      </li>
                    </ul>
                  </div>
                </div> <!-- end .d-flex-->
              </div> <!-- end col -->
            </div>
            <!-- end task -->
            <!-- task -->
            <div class="row justify-content-sm-between mt-2">
              <div class="col-sm-6 mb-2 mb-sm-0">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="task3">
                  <label class="form-check-label" for="task3">
                    Write a release note
                  </label>
                </div> <!-- end checkbox -->
              </div> <!-- end col -->
              <div class="col-sm-6">
                <div class="d-flex justify-content-between">
                  <div id="tooltip-container2">

                  </div>
                  <div>
                    <ul class="list-inline fs-13 text-end mb-0">
                      <li class="list-inline-item">
                        <i class='ri-calendar-todo-line fs-16 me-1'></i> Aujourd'hui 6pm
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-list-check-3 fs-16 me-1'></i> 18/21
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-chat-2-line fs-16 me-1'></i> 73
                      </li>
                      <li class="list-inline-item ms-2">
                        <span class="badge bg-info-subtle text-info p-1">Medium</span>
                      </li>
                    </ul>
                  </div>
                </div> <!-- end .d-flex-->
              </div> <!-- end col -->
            </div>
            <!-- end task -->
            <!-- task -->
            <div class="row justify-content-sm-between mt-2">
              <div class="col-sm-6 mb-2 mb-sm-0">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="task3">
                  <label class="form-check-label" for="task3">
                    Write a release note
                  </label>
                </div> <!-- end checkbox -->
              </div> <!-- end col -->
              <div class="col-sm-6">
                <div class="d-flex justify-content-between">
                  <div id="tooltip-container2">

                  </div>
                  <div>
                    <ul class="list-inline fs-13 text-end mb-0">
                      <li class="list-inline-item">
                        <i class='ri-calendar-todo-line fs-16 me-1'></i> Aujourd'hui 6pm
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-list-check-3 fs-16 me-1'></i> 18/21
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-chat-2-line fs-16 me-1'></i> 73
                      </li>
                      <li class="list-inline-item ms-2">
                        <span class="badge bg-info-subtle text-info p-1">Medium</span>
                      </li>
                    </ul>
                  </div>
                </div> <!-- end .d-flex-->
              </div> <!-- end col -->
            </div>
            <!-- end task -->
            <!-- task -->
            <div class="row justify-content-sm-between mt-2">
              <div class="col-sm-6 mb-2 mb-sm-0">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="task3">
                  <label class="form-check-label" for="task3">
                    Write a release note
                  </label>
                </div> <!-- end checkbox -->
              </div> <!-- end col -->
              <div class="col-sm-6">
                <div class="d-flex justify-content-between">
                  <div id="tooltip-container2">

                  </div>
                  <div>
                    <ul class="list-inline fs-13 text-end mb-0">
                      <li class="list-inline-item">
                        <i class='ri-calendar-todo-line fs-16 me-1'></i> Aujourd'hui 6pm
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-list-check-3 fs-16 me-1'></i> 18/21
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-chat-2-line fs-16 me-1'></i> 73
                      </li>
                      <li class="list-inline-item ms-2">
                        <span class="badge bg-info-subtle text-info p-1">Medium</span>
                      </li>
                    </ul>
                  </div>
                </div> <!-- end .d-flex-->
              </div> <!-- end col -->
            </div>
            <!-- end task -->
            <!-- task -->
            <div class="row justify-content-sm-between mt-2">
              <div class="col-sm-6 mb-2 mb-sm-0">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="task3">
                  <label class="form-check-label" for="task3">
                    Write a release note
                  </label>
                </div> <!-- end checkbox -->
              </div> <!-- end col -->
              <div class="col-sm-6">
                <div class="d-flex justify-content-between">
                  <div id="tooltip-container2">

                  </div>
                  <div>
                    <ul class="list-inline fs-13 text-end mb-0">
                      <li class="list-inline-item">
                        <i class='ri-calendar-todo-line fs-16 me-1'></i> Aujourd'hui 6pm
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-list-check-3 fs-16 me-1'></i> 18/21
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-chat-2-line fs-16 me-1'></i> 73
                      </li>
                      <li class="list-inline-item ms-2">
                        <span class="badge bg-info-subtle text-info p-1">Medium</span>
                      </li>
                    </ul>
                  </div>
                </div> <!-- end .d-flex-->
              </div> <!-- end col -->
            </div>
            <!-- end task -->
            <!-- task -->
            <div class="row justify-content-sm-between mt-2">
              <div class="col-sm-6 mb-2 mb-sm-0">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="task3">
                  <label class="form-check-label" for="task3">
                    Write a release note
                  </label>
                </div> <!-- end checkbox -->
              </div> <!-- end col -->
              <div class="col-sm-6">
                <div class="d-flex justify-content-between">
                  <div id="tooltip-container2">

                  </div>
                  <div>
                    <ul class="list-inline fs-13 text-end mb-0">
                      <li class="list-inline-item">
                        <i class='ri-calendar-todo-line fs-16 me-1'></i> Aujourd'hui 6pm
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-list-check-3 fs-16 me-1'></i> 18/21
                      </li>
                      <li class="list-inline-item ms-1">
                        <i class='ri-chat-2-line fs-16 me-1'></i> 73
                      </li>
                      <li class="list-inline-item ms-2">
                        <span class="badge bg-info-subtle text-info p-1">Medium</span>
                      </li>
                    </ul>
                  </div>
                </div> <!-- end .d-flex-->
              </div> <!-- end col -->
            </div>
            <!-- end task -->
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title">Rappel des Factures</h4>
        <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
          <thead>
            <tr>
              <th>Num ▼ </th>
              <th>Fournisseur ▼ </th>
              <th>Client ▼ </th>
              <th>Projet ▼ </th>
              <th>Methode de Payment ▼ </th>
              <th>Montant DH ▼ </th>
              <th>Date ▼ </th>
              <th>PDF ▼ </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Fournisseur A</td>
              <td>Client X</td>
              <td>Projet Alpha</td>
              <td>Carte de Crédit</td>
              <td>1000</td>
              <td>2023-01-01</td>
              <td><a href="#">Download</a></td>
            </tr>
            <tr>
              <td>2</td>
              <td>Fournisseur B</td>
              <td>Client Y</td>
              <td>Projet Beta</td>
              <td>Virement Bancaire</td>
              <td>2000</td>
              <td>2023-02-01</td>
              <td><a href="#">Download</a></td>
            </tr>
            <tr>
              <td>3</td>
              <td>Fournisseur C</td>
              <td>Client Z</td>
              <td>Projet Gamma</td>
              <td>Espèces</td>
              <td>1500</td>
              <td>2023-03-01</td>
              <td><a href="#">Download</a></td>
            </tr>
            <tr>
              <td>4</td>
              <td>Fournisseur D</td>
              <td>Client W</td>
              <td>Projet Delta</td>
              <td>Chèque</td>
              <td>2500</td>
              <td>2023-04-01</td>
              <td><a href="#">Download</a></td>
            </tr>
            <tr>
              <td>5</td>
              <td>Fournisseur E</td>
              <td>Client V</td>
              <td>Projet Epsilon</td>
              <td>Carte de Crédit</td>
              <td>3000</td>
              <td>2023-05-01</td>
              <td><a href="#">Download</a></td>
            </tr>
          </tbody>
        </table>

      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div> <!-- end row-->


@endsection
@push('styles')

@endpush
@push('scripts')
<!-- Apex Charts js -->
<script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/demo.dashboard.js') }}"></script>

@endpush