@extends('includes.body.navigation')

@section('navigation_list')
    <div class="navbar-collapse" style="display: flex;justify-content: center;">
        <ul class="nav navbar-nav" id="sidenav01">
            <li >
                <a href="{{route('admin.home')}}" data-parent="#sidenav01" class="collapsed">
                    <h4>
                        {{__('admin_pages.page_navigation_top')}}
                    </h4>
                </a>
            </li>
            <li><a href="{{route('admin.home')}}"><span  class="glyphicon glyphicon-dashboard"></span> {{__('admin_pages.page_navigation_list_dashboard')}}</a></li>
            <li><a href="{{route('admin.approvals.index')}}"><i class="far fa-calendar-check"></i> {{__('admin_pages.page_navigation_option_approvals')}}</a></li>
            <li><a href="{{route('admin.bookings.index')}}"><i class="far fa-address-book"></i> {{__('admin_pages.page_navigation_option_booking')}}</a></li>
            <li><a href="{{route('admin.schedules.index')}}"><i class="far fa-clock"></i> {{__('admin_pages.page_navigation_option_schedules')}}</a></li>
            <li >
                <a href="#" data-toggle="collapse" data-target="#toggleDemo4" data-parent="#sidenav01" class="collapsed">
                    <i class="fas fa-bus"></i> {{__('admin_pages.page_navigation_list_buses')}} <span class="caret"></span>
                </a>
                <div class="collapse" id="toggleDemo4" style="height: 0px;">
                    <ul class="nav nav-list" style="padding-left: 15px">
                        <li><a href="{{route('admin.buses.index')}}"><i class="fas fa-angle-right"></i> {{__('admin_pages.page_navigation_sub_list_buses')}}</a></li>
                        <li><a href="{{route('admin.bus-routes.index')}}"><i class="fas fa-angle-right"></i> {{__('admin_pages.page_navigation_sub_list_bus_routes')}}</a></li>
                        <li><a href="{{route('admin.bustype.index')}}"><i class="fas fa-angle-right"></i> {{__('admin_pages.page_navigation_sub_list_bus_types')}}</a></li>
                    </ul>
                </div>
            </li>
            <li><a href="{{route('admin.route.index')}}"><i class="fas fa-code-branch"></i> {{__('admin_pages.page_navigation_list_routes')}}</a></li>
            <li><a href="{{route('admin.location.index')}}"><i class="fas fa-location-arrow"></i></span> {{__('admin_pages.page_navigation_list_locations')}}</a></li>
            <li >
                <a href="#" data-toggle="collapse" data-target="#toggleDemo2" data-parent="#sidenav01" class="collapsed">
                    <span class="glyphicon glyphicon-cog"></span> {{__('admin_pages.page_navigation_list_settings')}} <span class="caret"></span>
                </a>
                <div class="collapse" id="toggleDemo2" style="height: 0px;">
                    <ul class="nav nav-list" style="padding-left: 15px">
                        <li><a href="#"><i class="fas fa-angle-right"></i> Option 1</a></li>
                    </ul>
                </div>
            </li>
            <li><a href="{{route('admin.logout')}}"><span class="glyphicon glyphicon-log-out"></span> {{__('admin_pages.page_navigation_list_logout')}}</a></li>
        </ul>
    </div>
@endsection

