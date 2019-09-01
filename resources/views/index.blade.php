<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/scripts.js')}}"></script>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="links">
            <span class="col-md-4"><a href="{{ url('by_date_by_iso') }}" class="btn btn-primary">Rates By Date By ISO</a></span>
            <span class="col-md-4"><a href="{{ url('draw_graphic') }}" class="btn btn-primary">Draw graphic</a></span>
        </div>
    </div>
    @yield('by_date_by_iso')
    @yield('draw_graphic')
</div>


</body>
</html>
