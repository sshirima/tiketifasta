<li class="{{Request::is('admin/reports/collections/merchants') ? 'active' : ''}}"><a href="{{route('admin.collection_reports.merchants')}}" >By merchant</a></li>
<li class="{{Request::is('admin/reports/collections/buses') ? 'active' : ''}}"><a href="{{route('admin.collection_reports.buses')}}" >By Buses</a></li>
<li class="{{Request::is('admin/reports/collections/daily') ? 'active' : ''}}"><a href="{{route('admin.collection_reports.daily')}}" >By date</a></li>
<li class="{{Request::is('admin/reports/collections/tickets') ? 'active' : ''}}"><a href="{{route('admin.tickets_count.daily')}}" >Tickets</a></li>
