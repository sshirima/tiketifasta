<li class="{{Request::is('admin/reports/disbursement/merchants') ? 'active' : ''}}"><a href="{{route('admin.disbursement_reports.merchants')}}" >Merchant report</a></li>
<li class="{{Request::is('admin/reports/disbursement/buses') ? 'active' : ''}}"><a href="{{route('admin.disbursement_reports.buses')}}" >Buses report</a></li>
<li class="{{Request::is('admin/reports/disbursement/daily') ? 'active' : ''}}"><a href="{{route('admin.disbursement_reports.daily')}}" >Daily report</a></li>
<li class="{{Request::is('admin/reports/disbursement/bookings') ? 'active' : ''}}"><a href="{{route('admin.disbursement_reports.bookings')}}" >Booking report</a></li>
