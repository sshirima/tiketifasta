@extends('users.layouts.master_v2')

@section('title')
User | Dashboard
@endsection

@section('content')
    <section class="features-icons">
        <div class="nav-side-menu pull-left">
            <div class="brand">Dashboard</div>
            <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content">

            </i>
            <div class="menu-list">

                <ul id="menu-content" class="menu-content collapse out">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>

                    {{--<li  data-toggle="collapse" data-target="#products" class="collapsed active">
                        <a href="#"><i class="fa fa-gift fa-lg"></i> UI Elements <span class="arrow"></span></a>
                    </li>
                    <ul class="sub-menu collapse" id="products">
                        <li class="active"><a href="#">CSS3 Animation</a></li>
                        <li><a href="#">General</a></li>
                        <li><a href="#">Buttons</a></li>
                        <li><a href="#">Tabs & Accordions</a></li>
                        <li><a href="#">Typography</a></li>
                        <li><a href="#">FontAwesome</a></li>
                        <li><a href="#">Slider</a></li>
                        <li><a href="#">Panels</a></li>
                        <li><a href="#">Widgets</a></li>
                        <li><a href="#">Bootstrap Model</a></li>
                    </ul>--}}


                    {{--<li data-toggle="collapse" data-target="#service" class="collapsed">
                        <a href="#"><i class="fa fa-globe fa-lg"></i> Services <span class="arrow"></span></a>
                    </li>
                    <ul class="sub-menu collapse" id="service">
                        <li>New Service 1</li>
                        <li>New Service 2</li>
                        <li>New Service 3</li>
                    </ul>--}}


                    {{--<li data-toggle="collapse" data-target="#new" class="collapsed">
                        <a href="#"><i class="fa fa-car fa-lg"></i> New <span class="arrow"></span></a>
                    </li>
                    <ul class="sub-menu collapse" id="new">
                        <li>New New 1</li>
                        <li>New New 2</li>
                        <li>New New 3</li>
                    </ul>--}}


                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-user fa-lg"></i> Profile
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{route('logout')}}">
                            <i class="fas fa-sign-out-alt"></i>  Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </section>
@endsection

@section('import_css')
    <link rel="stylesheet" href="{{asset('css/user_dashboard_vertical_menu.css')}}">
@endsection