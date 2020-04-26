<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="@yield('description')">
  <meta name="author" content="Seong Bae">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title')</title>

  <link href="{{ asset('canvas/css/fontawesome.min.css') }}" rel="stylesheet" >
  <link href="{{ asset('canvas/css/all.min.css') }}" rel="stylesheet" >
  <link href="{{ asset('canvas/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('canvas/css/adminlte.min.css') }}" rel="stylesheet">
  <link href="{{ asset('canvas/css/datatables.min.css') }}" rel="stylesheet" >
  <link href="{{ asset('canvas/css/custom.css') }}" rel="stylesheet">
  
  // First try loading jQuery from Google's CDN
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

  // Fall back to a local copy of jQuery if the CDN fails
  <script>
  window.jQuery || document.write('<script src="http://mysite.com/jquery.min.js"><\/script>'))
  </script>
</head>

<body id="page-top">

<div id="app">
  @include('canvas::frontend.nav')

  <div id="page-content">
    @yield('content')
  </div>
  
  @include('canvas::frontend.footer')

</div>

<script src="{{ asset('canvas/js/jquery.min.js') }}"></script>
<script src="{{ asset('canvas/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('canvas/js/adminlte.min.js') }}"></script>
<script src="{{ asset('canvas/js/datatables.min.js') }}"></script>


@stack('scripts')

</body>

</html>
