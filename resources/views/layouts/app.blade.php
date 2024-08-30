@extends('layouts.main')

@section('main-content')
<div class="wrapper">
  @include('partials._header')
  @include('partials._sidebar')
  <div class="content-page">
    <div class="content">

      <!-- Start Content-->
      
      <div class="container-fluid">
        @yield('content')
      </div>
    </div>
  </div>
  {{-- @include('partials._footer') --}}
</div>
@endsection