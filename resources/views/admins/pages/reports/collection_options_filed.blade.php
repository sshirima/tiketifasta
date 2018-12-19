<li class="{{Request::is('admin/reports/collections/merchants') ? 'active' : ''}}"><a href="{{route('admin.collection_reports.merchants')}}" >Merchant report</a></li>
<li class="{{Request::is('admin/reports/collections/buses') ? 'active' : ''}}"><a href="{{route('admin.collection_reports.buses')}}" >Buses report</a></li>
<li class="{{Request::is('admin/reports/collections/daily') ? 'active' : ''}}"><a href="{{route('admin.collection_reports.daily')}}" >Daily report</a></li>
<li class="{{Request::is('admin/reports/collections/bookings') ? 'active' : ''}}"><a href="{{route('admin.collection_reports.bookings')}}" >Booking report</a></li>
