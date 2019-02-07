<html lang="en"><head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{asset('landing_page/vendor/bootstrap/css/bootstrap.min.css')}}">

    <!-- Custom fonts for this template -->
    <link rel="stylesheet" href="{{asset('landing_page/vendor/font-awesome/css/font-awesome.min.css')}}">

    <link href="{{asset('landing_page/css/simple-line-icons.css')}}" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="{{asset('landing_page/css/landing-page.min.css')}}">

    @yield('import_css')

    <style type="text/css">
        html, body {
            height: 100%;
            margin: 0;
        }

        .wrapper {
            min-height: 80%;

            /* Equal to height of footer */
            /* But also accounting for potential margin-bottom of last child */
            margin-bottom: -50px;
        }

        .footer,
        .push {
            height: 80px;
            position: relative;
            width: 100%;
        }

        .center-footer {
            text-align: center;
        }

    </style>
</head>

<body>

<!-- Navigation -->
@yield('header')

<!-- Content -->
<div class="wrapper">
    @yield('content')
</div>

<!-- Footer -->
@yield('footer')

{{--<script src="{{asset('landing_page/vendor/jquery/jquery.min.js')}}"></script>--}}
<script src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous">
</script>

<!-- Bootstrap 3.3.7 -->
<script src="{{asset('landing_page/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
@yield('import_js')
</body>
</html>