<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset('adminlte/dist/img/boxed-bg.png')}}" class="img-circle"
                     alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{$merchant[\App\Models\Staff::COLUMN_FIRST_NAME].' '.$merchant[\App\Models\Staff::COLUMN_LAST_NAME]}}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> {{__('Online')}}</a>
            </div>
        </div>

        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="{{__('Search')}}">
                <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
            </div>
        </form>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">{{__('General settings')}}</li>
            <li class="{{Request::is('merchant') ? 'active' : ''}}"><a href="{{route('merchant.home')}}"><i class="fas fa-tachometer-alt"></i> <span> {{__('merchant_side_bar_left.option_dashboard')}}</span></a></li>
            {{--<li class="{{Request::is('merchant/tickets*') ? 'active' : ''}}"><a href="{{route('merchant.tickets.index')}}"><i class="fas fa-book"></i> <span> {{__('merchant_side_bar_left.option_tickets')}}</span></a></li>
            <li class="{{Request::is('merchant/bookings*') ? 'active' : ''}}"><a href="{{route('merchant.bookings.index')}}"><i class="fas fa-book"></i> <span> {{__('merchant_side_bar_left.option_bookings')}}</span></a></li>
            <li class="{{Request::is('merchant/schedules*') ? 'active' : ''}}"><a href="{{route('merchant.schedules.index')}}"><i class="fas fa-bus"></i> <span> {{__('merchant_side_bar_left.option_schedules')}}</span></a></li>
            --}}
            <li class="treeview">
                <a href="#">
                    <i class="fas fa-money-bill-wave"></i><span> Payment transactions</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li class="{{Request::is('merchant/collection/transactions/all') ? 'active' : ''}}"><a href="{{route('merchant.collection.transactions.all')}}"><i class="fas fa-arrow-circle-right"></i> Collections</a></li>
                    <li class="{{Request::is('merchant/disbursement/transactions/all') ? 'active' : ''}}"><a href="{{route('merchant.disbursement.transactions.all')}}"><i class="fas fa-arrow-circle-right"></i> Payments</a></li>
                    {{--<li class="{{Request::is('admin/merchant-payments*') ? 'active' : ''}}"><a href="{{route('admin.merchant_payments.summary')}}"><i class="fas fa-arrow-circle-right"></i> Disbursements transactions</a></li>
                --}}</ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="far fa-clock"></i> <span>Schedules</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li class="{{Request::is('merchant/schedules*') ? 'active' : ''}}"><a href="{{route('merchant.schedules.index')}}"><i class="fas fa-arrow-circle-right"></i> <span> View schedules</span></a></li>
                    <li class="{{Request::is('merchant/tickets') ? 'active' : ''}}"><a href="{{route('merchant.tickets.index')}}"><i class="fas fa-arrow-circle-right"></i> <span> Tickets </span></a></li>
                    <li class="{{Request::is('merchant/bookings') ? 'active' : ''}}"><a href="{{route('merchant.bookings.index')}}"><i class="fas fa-arrow-circle-right"></i>  <span> Bookings </span></a></li>
                </ul>
            </li>
            {{--<li class="{{Request::is('merchant/buses*') ? 'active' : ''}}"><a href="{{route('merchant.buses.index')}}"><i class="fas fa-bus"></i> <span> {{__('merchant_side_bar_left.option_buses')}}</span></a></li>
            --}}
            <li class="treeview">
                <a href="#">
                    <i class="fas fa-bus"></i> <span>Buses</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li class="{{Request::is('merchant/buses*') ? 'active' : ''}}"><a href="{{route('merchant.buses.index')}}"><i class="fas fa-arrow-circle-right"></i> <span> View buses</span></a></li>
                    <li class="{{Request::is('merchant/routes*') ? 'active' : ''}}"><a href="{{route('merchant.routes.index')}}"><i class="fas fa-arrow-circle-right"></i> <span> Routes </span></a></li>
                </ul>
            </li>
            <li class="header">{{__('merchant_side_bar_left.header_title_account')}}</li>
            <li class="{{Request::is('merchant/staff*') ? 'active' : ''}}"><a href="{{route('merchant.staff.index')}}"><i class="fas fa-users-cog"></i> <span> {{__('merchant_side_bar_left.option_staffs')}}</span></a></li>
            <li ><a href="{{route('merchant.logout')}}"><i class="fas fa-sign-out-alt"></i> <span> {{__('merchant_side_bar_left.option_logout')}}</span></a></li>
        </ul>
    </section>
</aside>