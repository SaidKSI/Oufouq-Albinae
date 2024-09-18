@extends('layouts.app')

@section('content')
<x-Breadcrumb title="Dashboard" />
<div class="row">
  <div class="col-xl-6">
    <div class="card">
      <div class="card-body">
        <div dir="ltr">
          <div id="line-chart-zoomable" class="apex-charts" data-colors="#fa5c7c"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-6">
    <div class="card">
      <h4 class="text-center m-1">Tasks</h4>
      <div class="card-body my-3">
        @if($tasks->count() > 0)
        @foreach ($tasks as $task)
        <div class="row justify-content-sm-between">
          <div class="col-sm-6 mb-2 mb-sm-0">
            <div class="form-check">
              <input type="checkbox" class="form-check-input task-checkbox" id="task{{ $task->id }}"
                data-id="{{ $task->id }}" {{ $task->status == 'complete' ? 'checked' : '' }}>
              <label
                class="form-check-label text-center {{ $task->status == 'complete' ? 'text-decoration-line-through' : '' }}"
                for="task{{ $task->id }}">
                {{$task->name}} <br>
                <small>{{$task->description}}</small>
              </label>
            </div> <!-- end checkbox -->
          </div> <!-- end col -->
          <div class="col-sm-6">
            <div class="d-flex justify-content-end">
              <div id="tooltip-container">
                @foreach ($task->employees as $employer)
                <span class="badge bg-primary">{{ $employer->full_name }}</span>
                @endforeach
                <div>
                  <ul class="list-inline fs-13 text-end">
                    <li class="list-inline-item">
                      <i class="ri-calendar-todo-line fs-16 me-1"></i> {{$task->created_at->diffForHumans()}}
                    </li>
                    <li class="list-inline-item text-end ms-1">
                      <span class="badge bg-danger-subtle text-danger p-1">{{$task->priority}}</span>
                    </li>
                  </ul>
                </div>
              </div> <!-- end .d-flex-->
            </div> <!-- end col -->
          </div>
          @endforeach
          @else
          <div class="text-center">
            <p>No tasks available</p>
          </div>
          @endif
        </div>
      </div>
    </div>


    @endsection

    @push('scripts')
    <script src="{{ asset('assets/vendor/dragula/dragula.min.js') }}"></script>
    <!-- Apex Charts js -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
      fetch('{{ route('company.capital.history') }}')
          .then(response => response.json())
          .then(data => {
              const dates = data.map(entry => entry.date);
              const capital = data.map(entry => entry.capital);

              var options = {
                  chart: {
                      type: 'line',
                      height: 380,
                      zoom: { enabled: true },
                  },
                  series: [{
                      name: 'Company Capital',
                      data: capital
                  }],
                  xaxis: {
                      categories: dates,
                      type: 'datetime',
                      title: { text: 'Date' }
                  },
                  yaxis: {
                      title: { text: 'Capital' },
                      labels: {
                          formatter: function (value) {
                              return value.toFixed(2);
                          }
                      }
                  },
                  title: { text: 'Company Capital History', align: 'left' },
                  grid: {
                      row: { colors: ['transparent', 'transparent'], opacity: 0.2 },
                      borderColor: '#f1f3fa',
                  },
                  stroke: { width: [3], curve: 'smooth' },
                  markers: { size: 0, style: 'full' },
                  fill: {
                      gradient: {
                          enabled: true,
                          shadeIntensity: 1,
                          inverseColors: false,
                          opacityFrom: 0.5,
                          opacityTo: 0.1,
                          stops: [0, 70, 80, 100],
                      },
                  },
                  tooltip: {
                      shared: false,
                      y: {
                          formatter: function (value) {
                              return value.toFixed(2);
                          }
                      }
                  },
                  responsive: [
                      {
                          breakpoint: 600,
                          options: {
                              chart: { toolbar: { show: false } },
                              legend: { show: false },
                          },
                      },
                  ],
              };

              var chart = new ApexCharts(document.querySelector("#line-chart-zoomable"), options);
              chart.render();
          })
          .catch(error => console.error('Error fetching capital history:', error));
  });
    </script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.task-checkbox').forEach(function (checkbox) {
      checkbox.addEventListener('change', function () {
        var taskId = this.getAttribute('data-id');
        var isChecked = this.checked;
        var status = isChecked ? 'complete' : 'pending';

        fetch(`/dashboard/tasks/${taskId}/update-status`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ status: status })
        }).then(response => response.json())
          .then(data => {
            if (data.success) {
              var label = document.querySelector(`label[for="task${taskId}"]`);
              if (isChecked) {
                label.classList.add('text-decoration-line-through');
              } else {
                label.classList.remove('text-decoration-line-through');
              }
            } else {
              console.error('Failed to update task status');
            }
          }).catch(error => console.error('Error:', error));
      });
    });
  });
    </script>
    @endpush