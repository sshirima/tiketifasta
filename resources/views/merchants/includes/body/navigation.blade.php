@extends('includes.body.navigation')

@section('navigation_list')
    <div class="navbar-collapse" style="display: flex;justify-content: center;">
        <ul class="nav navbar-nav" id="sidenav01">
            <li >
                <a href="#" data-toggle="collapse" data-target="#toggleDemo0" data-parent="#sidenav01" class="collapsed">
                    <h4> {{__('merchant_pages.page_navigation_title')}}</h4>
                </a>
            </li>
            <li ><a href="{{route('merchant.home')}}"><span class="glyphicon glyphicon-dashboard"></span> {{__('merchant_pages.page_navigation_option_dashboard')}}</a></li>
            <li><a href="{{route('merchant.bookings.index')}}"><i class="far fa-address-book"></i> {{__('merchant_pages.page_navigation_option_booking')}}</a></li>
            <li >
                <a href="#" data-toggle="collapse" data-target="#toggleDemo3" data-parent="#sidenav01" class="collapsed">
                    <i class="fas fa-bus"></i></span> {{__('merchant_pages.page_navigation_option_buses')}} <span class="caret"></span>
                </a>
                <div class="collapse" id="toggleDemo3" style="height: 0px;">
                    <ul class="nav nav-list" style="padding-left: 15px">
                        <li><a href="{{route('merchant.buses.index')}}"><i class="fas fa-angle-right"></i> {{__('merchant_pages.page_navigation_sub_option_view_buses')}}</a></li>
                    </ul>
                </div>
            </li>
            <li >
                <a href="#" data-toggle="collapse" data-target="#toggleDemo2" data-parent="#sidenav01" class="collapsed">
                    <span class="glyphicon glyphicon-cog"></span> {{__('merchant_pages.page_navigation_option_settings')}} <span class="caret"></span>
                </a>
                <div class="collapse" id="toggleDemo2" style="height: 0px;">
                    <ul class="nav nav-list" style="padding-left: 15px">
                        <li><a href="{{route('merchant.staff.index')}}"><i class="fas fa-angle-right"></i> {{__('merchant_pages.page_navigation_sub_option_view_staff')}}</a></li>
                    </ul>
                </div>
            </li>
            <li><a href="{{route('merchant.logout')}}"><span class="glyphicon glyphicon-log-out"></span> {{__('merchant_pages.page_navigation_option_logout')}}</a></li>
        </ul>
    </div>
@endsection

