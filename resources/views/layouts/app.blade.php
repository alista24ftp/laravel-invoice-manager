<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Invoice Manager')</title>

  <!-- Styles -->
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  @yield('customstyles')

</head>

<body>
  <div id="app" class="{{ route_class() }}-page">

    <div class="container">

      @include('shared._messages')
      @if(Auth::check())
        <div class="row">
          <div class="col-3">
            @include('layouts._nav')
          </div>
          <div class="col-9">
            @yield('content')
          </div>
        </div>

      @else
        @yield('content')
      @endif

    </div>

    @include('layouts._footer')
  </div>

  <!-- Scripts -->
  <script src="{{ mix('js/app.js') }}"></script>
  @yield('customjs')
</body>

</html>
