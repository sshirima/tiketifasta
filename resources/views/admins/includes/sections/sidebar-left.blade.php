<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset('adminlte/dist/img/boxed-bg.png')}}" class="img-circle"
                     alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{$admin[\App\Models\Admin::COLUMN_FIRST_NAME].' '.$admin[\App\Models\Admin::COLUMN_LAST_NAME]}}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> {{__('admin_side_bar_left.status_online')}}</a>
            </div>
        </div>

        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="{{__('admin_side_bar_left.field_input_placeholder_search')}}">
                <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
            </div>
        </form>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">{{__('admin_side_bar_left.header_title_general')}}</li>
            <li class="{{Request::is('admin') ? 'active' : ''}}"><a href="{{route('admin.home')}}"><i class="fas fa-tachometer-alt"></i> <span> {{__('admin_side_bar_left.option_dashboard')}}</span></a></li>
            <li class="{{Request::is('admin/approvals') ? 'active' : ''}}"><a href="{{route('admin.approvals.index')}}"><i class="fas fa-link"></i> <span> {{__('admin_side_bar_left.option_approvals')}}</span></a></li>
            <li class="{{Request::is('admin/bookings') ? 'active' : ''}}"><a href="{{route('admin.bookings.index')}}"><i class="fas fa-link"></i> <span> {{__('admin_side_bar_left.option_bookings')}}</span></a></li>
            <li class="{{Request::is('admin/schedules*') ? 'active' : ''}}"><a href="{{route('admin.schedules.index')}}"><i class="fas fa-link"></i> <span> {{__('admin_side_bar_left.option_schedules')}}</span></a></li>
            <li class="{{Request::is('admin/buses*') ? 'active' : ''}}"><a href="{{route('admin.buses.index')}}"><i class="fas fa-link"></i> <span> {{__('admin_side_bar_left.option_buses')}}</span></a></li>
            <li class="{{Request::is('admin/bus-route*') ? 'active' : ''}}"><a href="{{route('admin.bus-routes.index')}}"><i class="fas fa-link"></i> <span> {{__('admin_side_bar_left.option_bus_routes')}}</span></a></li>
            <li class="{{Request::is('admin/bustype*') ? 'active' : ''}}"><a href="{{route('admin.bustype.index')}}"><i class="fas fa-link"></i> <span> {{__('admin_side_bar_left.option_bus_types')}}</span></a></li>
            <li class="{{Request::is('admin/merchants*') ? 'active' : ''}}"><a href="{{route('admin.merchant.index')}}"><i class="fas fa-link"></i> <span> {{__('admin_side_bar_left.option_merchants')}}</span></a></li>
            <li class="{{Request::is('admin/routes*') ? 'active' : ''}}"><a href="{{route('admin.route.index')}}"><i class="fas fa-code-branch"></i> <span> {{__('admin_side_bar_left.option_routes')}}</span></a></li>
            <li class="{{Request::is('admin/locations*') ? 'active' : ''}}"><a href="{{route('admin.location.index')}}"><i class="fas fa-location-arrow"></i> <span> {{__('admin_side_bar_left.option_locations')}}</span></a></li>
            <li class="{{Request::is('accounts/admins*') ? 'active' : ''}}"><a href="{{route('admin.admin_accounts.index')}}"><i class="fas fa-link"></i> <span> {{__('admin_side_bar_left.option_accounts_admin')}}</span></a></li>

            <li class="header">{{__('admin_side_bar_left.header_title_account')}}</li>
            <li ><a href="{{route('admin.logout')}}"><i class="fas fa-sign-out-alt"></i> <span> {{__('admin_side_bar_left.option_logout')}}</span></a></li>
        </ul>
    </section>
</aside>