@extends('layouts.header')

@section('header-content')
    <div id="mobilebgfix">
        <div class="mobile-bg-fix-img-wrap">
            <div class="mobile-bg-fix-img"></div>
        </div>
        <div class="mobile-bg-fix-whole-site"><a class="skip-link screen-reader-text" href="#content">Skip to content</a>
            <div class="preloader" style="display: none;">
                <div class="status" style="display: none;">&nbsp;</div>
            </div>
            <header itemscope="" itemtype="http://schema.org/WPHeader" id="masthead" role="banner"
                    data-stellar-background-ratio="0.5" class="header header-style-one site-header" style="opacity: 1;">
                <div class="overlay-layer-nav sticky-navigation-open" style="min-height: 70px;">
                    <div class="navbar navbar-inverse bs-docs-nav navbar-fixed-top sticky-navigation appear-on-scroll">
                        <div class="container">
                            <div class="navbar-header">
                                @include('includes.header.brand')
                            </div>
                            <div itemscope="" itemtype="http://schema.org/SiteNavigationElement" aria-label="Primary Menu"
                                 id="menu-primary" class="navbar-collapse collapse in" aria-expanded="true" style="">
                                <div id="site-header-menu" class="site-header-menu toggled-on">
                                    <nav id="site-navigation" class="main-navigation" role="navigation">
                                        <div id="mega-menu-wrap-primary" class="mega-menu-wrap">
                                            <div class="mega-menu-toggle" tabindex="0">
                                                <div class="mega-toggle-block mega-menu-toggle-block mega-toggle-block-right mega-toggle-block-1"
                                                     id="mega-toggle-block-1"></div>
                                            </div>
                                            <ul id="mega-menu-primary" class="mega-menu mega-menu-horizontal"
                                                data-event="hover_intent" data-effect="disabled" data-effect-speed="200"
                                                data-effect-mobile="disabled" data-effect-speed-mobile="200"
                                                data-second-click="close" data-document-click="collapse"
                                                data-vertical-behaviour="standard" data-breakpoint="600" data-unbind="true">
                                                <li class="mega-menu-item mega-menu-item-type-custom mega-menu-item-object-custom mega-current-menu-item mega-current_page_item mega-menu-item-home mega-align-bottom-left mega-menu-flyout mega-menu-item-6158"
                                                    id="mega-menu-item-6158"><a class="mega-menu-link"
                                                                                href="{{route('user.home')}}"
                                                                                tabindex="0"><i class="fas fa-home"></i>
                                                        Home</a></li>
                                                <li class="mega-menu-item mega-menu-item-type-custom mega-menu-item-object-custom mega-current-menu-item mega-current_page_item mega-menu-item-home mega-align-bottom-left mega-menu-flyout mega-menu-item-6158"
                                                    id="mega-menu-item-6159"><a class="mega-menu-link"
                                                                                href="https://demo.themeisle.com/parallax-one/#story"
                                                                                tabindex="1"><i class="fas fa-times"></i>
                                                        Cancel ticket</a></li>
                                                <li class="mega-menu-item mega-menu-item-type-custom mega-menu-item-object-custom mega-current-menu-item mega-current_page_item mega-menu-item-home mega-align-bottom-left mega-menu-flyout mega-menu-item-6158"
                                                    id="mega-menu-item-6160"><a class="mega-menu-link"
                                                                                href="https://demo.themeisle.com/parallax-one/#story"
                                                                                tabindex="2"><i
                                                                class="fas fa-info-circle"></i> About</a></li>
                                                <li class="mega-menu-item mega-menu-item-type-custom mega-menu-item-object-custom mega-current-menu-item mega-current_page_item mega-menu-item-home mega-align-bottom-left mega-menu-flyout mega-menu-item-6160"
                                                    id="mega-menu-item-6161"><a class="mega-menu-link"
                                                                                href="https://demo.themeisle.com/parallax-one/#customers"
                                                                                tabindex="3"><i class="fas fa-comments"></i>
                                                        Testimonials</a></li>
                                                <li class="mega-menu-item mega-menu-item-type-post_type mega-menu-item-object-page mega-align-bottom-left mega-menu-flyout mega-menu-item-6165"
                                                    id="mega-menu-item-6165"><a class="mega-menu-link"
                                                                                href="https://demo.themeisle.com/parallax-one/contact/"
                                                                                tabindex="4"><i class="fas fa-phone"></i>
                                                        Contact</a></li>
                                                <li class="mega-menu-item mega-menu-item-type-post_type mega-menu-item-object-page mega-align-bottom-left mega-menu-flyout mega-menu-item-6165"
                                                    id="mega-menu-item-6166"><a class="mega-menu-link"
                                                                                href="{{route('login')}}"
                                                                                tabindex="5"><i
                                                                class="fas fa-sign-in-alt"></i> Sign in</a></li>
                                            </ul>
                                        </div>
                                    </nav>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="overlay">
                        @yield('booking-form')
                    </div>
                </div>
            </header>
        </div>
    </div>
    {{--<div class="col-md-4 col-md-offset-1" style="padding: 15px">

    </div>
    <div class="col-md-1">
        <div style="padding-top: 20px" class="pull-right">
            <span><a href="#" style="text-decoration:none;"><h4><strong>Merchant</strong></h4></a> </span>
        </div>
    </div>
    <div class="col-md-2">
        @if (Auth::guard('web')->check())
            @include('users.includes.header.components.profile')
        @else
            <div style="padding-top: 30px">
                <span><a href="{{route('login')}}" style="text-decoration:none;"><span class="glyphicon glyphicon-log-in"></span> Login</a> </span><span style="color:white;">|</span>
                <span><a href="{{route('register')}}" style="text-decoration:none;"> Register</a> </span><span style="color:white;">|</span>
                <span><a href="#" style="text-decoration:none;">Help</a></span>
            </div>
        @endif
    </div>--}}
@endsection