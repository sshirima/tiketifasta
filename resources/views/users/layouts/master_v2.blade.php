@extends('layouts.master_user_v2')


@section('header')
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">

        <div class="container">
            <a class="navbar-brand" href="{{route('user.home')}}"><b>tiketi</b>FASTA</a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">

                </ul>
                <ul class="navbar-nav navbar-right">
                    <li class="nav-item  {{Request::is('/') ? 'active' : ''}}">
                        <a class="nav-link" href="{{route('user.home')}}"><i class="fas fa-home"></i> @lang('Home')
                            <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item {{Request::is('about-us') ? 'active' : ''}}">
                        <a class="nav-link" href="{{route('user.about_us')}}"><i
                                    class="fas fa-info-circle"></i> @lang('About us')</a>
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
                        <a class="nav-link" href="{{route('user.contact_us')}}"><i
                                    class="fas fa-phone-volume"></i> {{__('Contact')}}</a>
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
                            <i class="fas fa-ticket-alt"></i> {{__('Ticket')}}
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item"
                               href="{{route('merchant.onboarding.form')}}">{{__('On boarding')}}</a>
                            <a class="dropdown-item"
                               href="{{route('user.verify.ticket.form')}}">{{__('Verify ticket')}}</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown ">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                            @if(Auth::guard('web')->check())
                                <span> <i class="far fa-user"></i> {{$user->firstname}}</span>
                            @else
                                <i class="fas fa-user-circle"></i> {{__('Account')}}
                            @endif
                        </a>
                        <div class="dropdown-menu">
                            @if(Auth::guard('web')->check())
                                <a class="dropdown-item" href="{{route('user.dashboard.show')}}"><i
                                            class="fas fa-tachometer-alt"></i> @lang('Dashboard')</a>
                                <a class="dropdown-item" href="{{route('logout')}}"><i
                                            class="fas fa-sign-out-alt"></i> @lang('Logout')</a>
                            @else
                                <a class="dropdown-item" href="{{route('register')}}"><i
                                            class="fas fa-user-plus"></i> @lang('Register')</a>
                                <a class="dropdown-item" href="{{route('login')}}"><i
                                            class="fas fa-sign-in-alt"></i> @lang('Login')</a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{route('merchant.home')}}">@lang('Merchant')
                                | @lang('Login')</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        @if(Request::segment(1) == 'en' ||Request::segment(1) == 'sw')
                            @if(Request::is('en'))
                                <a class="nav-link" href="{{config('app.url').'/'.'sw'}}"> @lang('LANG')</a>
                            @elseif(Request::is('sw'))
                                <a class="nav-link" href="{{config('app.url').'/'.'en'}}"> @lang('LANG')</a>
                            @else
                                @if(Request::segment(1) == 'en')
                                    <a class="nav-link"
                                       href="{{str_replace('/en/', '/sw/',config('app.url').'/'.Request::path().substr(Request::fullUrl(), strpos(Request::fullUrl(),'?')))}}"> @lang('LANG')</a>
                                @elseif(Request::segment(1) == 'sw')
                                    <a class="nav-link"
                                       href="{{str_replace('/sw/', '/en/',config('app.url').'/'.Request::path().substr(Request::fullUrl(), strpos(Request::fullUrl(),'?')))}}"> @lang('LANG')</a>
                                @endif
                            @endif

                        @else
                            @if(Request::is('/'))
                                <a class="nav-link" href="{{config('app.url').'/'.'sw'}}"> @lang('LANG')</a>
                            @else
                                <a class="nav-link"
                                   href="{{config('app.url').'/'.'sw'.'/'.Request::path().substr(Request::fullUrl(), strpos(Request::fullUrl(),'?'))}}"> @lang('LANG')</a>
                            @endif
                        @endif

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
                <b>@lang('Version')</b> 1.0.0
            </div>
            <strong>@lang('Copyright') © 2019 </strong> {{__('All rights reserved')}}
        </div>
    </footer>
@endsection