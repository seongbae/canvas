<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="@yield('description')">
  <meta name="author" content="LNI Digital Marketing">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @if (option('google_analytics_id'))
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id={{option('google_analytics_id')}}"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '{{option("google_analytics_id")}}');
  </script>

  @endif

  <title>@yield('title')</title>

  <link href="{{ asset('css/vendor.css') }}" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  
</head>

<body id="page-top">

<div id="app">
  @include(themePath('nav'))

  <!-- Header -->
  @if ($banner == 'large')
    @include (themePath('header'))
  @endif


  @yield('content')

  @include(themePath('footer'))

</div>

<script src="{{ asset('js/vendor.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>


@stack('scripts')

<script>
$(document).ready(function(){

    $(document).on("click", ".openImageDialog", function () {
        var image = $(this).data('id');
        var title = $(this).data('title');
        var description = $(this).data('description');

        $(".modal-body #mediaImage").attr("src", image);
        $(".modal-body #mediaTitle").html(title);
        $(".modal-body #mediaDescription").html(description);
    });

});

</script>
</body>

</html>
