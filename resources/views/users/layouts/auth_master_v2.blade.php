@extends('layouts.master_user_v2')

@section('import_css')
    <link rel="stylesheet" href="{{ URL::asset('css/signin.css') }}">
@endsection

@section('header')
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">

        <div class="container">
            <a class="navbar-brand" href="{{route('user.home')}}"><b>Admin</b>LTE</a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbarCollapse" style="">
                <ul class="navbar-nav mr-auto ">

                </ul>
                <ul class="navbar-nav navbar-right">

                    <li class="nav-item  {{Request::is('register') ? 'active' : ''}}">
                        <a class="nav-link" href="{{route('register')}}">Register</a>
                    </li>
                    <li class="nav-item  {{Request::is('login') ? 'active' : ''}}">
                        <a class="nav-link" href="{{route('login')}}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endsection


@section('footer')
    @include('layouts.footer_v2')
@endsection