@extends('layouts.master_user_v2')


@section('header')
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">

        <div class="container">
            <a class="navbar-brand" href="{{route('user.home')}}"><b>tiketi</b>FASTA</a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">

                </ul>
                <ul class="navbar-nav navbar-right">
                    <li class="nav-item  {{Request::is('/') ? 'active' : ''}}">
                        <a class="nav-link" href="{{route('user.home')}}">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item {{Request::is('about-us') ? 'active' : ''}}">
                        <a class="nav-link" href="{{route('user.about_us')}}">About</a>
                    </li>
                    {{--<li class="nav-item {{Request::is('verify-ticket') ? 'active' : ''}}">
                        <a class="nav-link" href="{{route('user.verify.ticket.form')}}">Verify ticket</a>

                    </li>
                    <li class="nav-item {{Request::is('merchant/onboarding-form') ? 'active' : ''}}">
                        <a class="nav-link" href="{{route('merchant.onboarding.form')}}">Boarding</a>
                    </li>--}}
                   {{-- <li class="nav-item">
                        <a class="nav-link " href="{{route('merchant.home')}}">Merchant</a>
                    </li>--}}
                    <li class="nav-item  {{Request::is('contact-us') ? 'active' : ''}}">
                        <a class="nav-link" href="{{route('user.contact_us')}}">Contact</a>
                    </li>
                    {{--<li class="nav-item">
                        <a class="nav-link" href="{{route('register')}}">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('login')}}">Login</a>
                    </li>--}}
                    <li class="nav-item dropdown {{Request::is('merchant/onboarding-form') ? 'active' : ''||
                    Request::is('verify-ticket') ? 'active' : ''}}">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                            Tickets
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{route('merchant.onboarding.form')}}">Boarding</a>
                            <a class="dropdown-item" href="{{route('user.verify.ticket.form')}}">Verify ticket</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown ">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                            @if(Auth::guard('web')->check())
                                <span> <i class="far fa-user"></i> {{$user->firstname}}</span>
                            @else
                                Accounts
                            @endif
                        </a>
                        <div class="dropdown-menu">
                            @if(Auth::guard('web')->check())
                                <a class="dropdown-item" href="{{route('user.dashboard.show')}}">Dashboard</a>
                                <a class="dropdown-item" href="{{route('logout')}}">Logout</a>
                            @else
                                <a class="dropdown-item" href="{{route('register')}}">Register</a>
                                <a class="dropdown-item" href="{{route('login')}}">Login</a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{route('merchant.home')}}">Merchant | Sign in</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endsection


@section('footer')
    <div class="push"></div>
    <footer class="footer" style="background-color:#ececec;">
        <div class="container">
            <div class="pull-right hidden-xs">
                <b>Version</b> 1.0.0
            </div>
            <strong>Copyright © 2014-2016 </strong> All rights
            reserved.
        </div>
    </footer>
@endsection