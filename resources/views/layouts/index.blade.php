<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.css') }}" />
    <link rel="stylesheet" href="{{ asset('lib/sweetalert2/sweetalert2.min.css') }}" />
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <style>
        .bg {
            background-image: url('{{ asset('images/word-bg.jpg')}}');
            background-size: auto;
            background-repeat: no-repeat;
        }

    </style>

    @yield('style')
</head>
<body class="bg">
    <div class="container" id="app">
        @yield('content')
    </div>

    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="{{asset('js/bootstrap.js')}}"></script>
    <script src="{{asset('js/date.js')}}"></script>
    <script src="{{asset('lib/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    @yield('js')
</body>
</html>
