<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Just another WordPress site by Themeisle">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Parallax One - Just another WordPress site by Themeisle">
    <meta property="og:description" content="Just another WordPress site by Themeisle">
    <meta property="og:site_name" content="Parallax One">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:description" content="Just another WordPress site by Themeisle">
    <meta name="twitter:title" content="Parallax One - Just another WordPress site by Themeisle">
    <link rel="dns-prefetch" href="//maxcdn.bootstrapcdn.com">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//demot-vertigostudio.netdna-ssl.com">
    <link rel="dns-prefetch" href="//s.w.org">
    <link rel="alternate" type="application/rss+xml" title="Parallax One » Feed"
          href="https://demo.themeisle.com/parallax-one/feed/">

    <!-- pass through the CSRF (cross-site request forgery) token -->
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>@yield('title')</title>

    <!-- Latest compiled Google JQuery -->
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Word press theme -->
    <link rel="stylesheet" id="parallax-one-style-css"
          href="https://demot-vertigostudio.netdna-ssl.com/parallax-one/wp-content/themes/Parallax-One/style.css?ver=1.0.0"
          type="text/css" media="all">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>

    <!-- Font awesome cdn-->
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ URL::asset('css/master.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/panel_body.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/left_navigation.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/okipa/styles.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/okipa/styles-min.min.css') }}">

    <style type="text/css">
        .panel-table .panel-body{
            padding:0;
        }

        .panel-table .panel-body .table-bordered{
            border-style: none;
            margin:0;
        }

        .panel-table .panel-body .table-bordered > thead > tr > th:first-of-type {
            text-align:center;
            width: 100px;
        }

        .panel-table .panel-body .table-bordered > thead > tr > th:last-of-type,
        .panel-table .panel-body .table-bordered > tbody > tr > td:last-of-type {
            border-right: 0px;
        }

        .panel-table .panel-body .table-bordered > thead > tr > th:first-of-type,
        .panel-table .panel-body .table-bordered > tbody > tr > td:first-of-type {
            border-left: 0px;
        }

        .panel-table .panel-body .table-bordered > tbody > tr:first-of-type > td{
            border-bottom: 0px;
        }

        .panel-table .panel-body .table-bordered > thead > tr:first-of-type > th{
            border-top: 0px;
        }

        .panel-table .panel-footer .pagination{
            margin:0;
        }

        /*
        used to vertically center elements, may need modification if you're not using default sizes.
        */
        .panel-table .panel-footer .col{
            line-height: 34px;
            height: 34px;
        }

        .panel-table .panel-heading .col h3{
            line-height: 30px;
            height: 30px;
        }

        .panel-table .panel-body .table-bordered > tbody > tr > td{
            line-height: 34px;
        }
    </style>
    @yield('custom-import')

</head>

<body>
<div class="row" style="background-color: rgb(0, 156, 187)">
    @yield('header')
</div>
<div style="padding: 5px" class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        @yield('nav-bar-horizontal')
    </div>
    <div class="col-md-1"></div>
</div>

<div style="padding: 5px" class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        @yield('page-summary')
    </div>
    <div class="col-md-1"></div>
</div>

<div class="wrapper row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        @yield('content')
        @yield('paginate-bar')
    </div>
    <div class="col-md-1"></div>
</div>


<div class="push"></div>
@yield('footer')
@yield('footer-imports')
</body>
</html>