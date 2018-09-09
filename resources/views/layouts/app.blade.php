<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>
  <script src="{{ asset('js/Chart.bundle.js') }}"></script>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body>
  <div id="app">
    <main class="py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-4">
            <div class="box" style="text-align:center;padding-top:16px;">
              <img src="{{ asset('img/logo.jpg') }}" height="256"/>
              <div class="links">
                <a href="/home">Home Page</a>
                <a href="/finish_week">Next Week</a>
                <a href="/finish_league">Finish League</a>
                <a href="/refresh">Restart League</a>
                <a href="/games">Show All Games</a>
              </div>
            </div>
          </div>
          <div class="col-8" style="padding-top:0;padding-bottom:0;padding-right:0;">
            @yield('content')
          </div>
        </div>
      </div>
    </main>
  </div>
  <script src="{{ asset('bootstrap/js/jquery-3.3.1.slim.min.js') }}"></script>
  <script src="{{ asset('bootstrap/js/popper.min.js') }}"></script>
  <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>
