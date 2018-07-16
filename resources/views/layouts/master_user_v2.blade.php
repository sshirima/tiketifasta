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
<div class="push"></div>
@yield('footer')

<!-- jQuery 3 -->
<script src="{{asset('landing_page/vendor/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('landing_page/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
@yield('import_js')
</body>
</html>