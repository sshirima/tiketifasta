<li class="{{Request::is('admin/schedules') ? 'active' : ''}}"><a href="{{route('admin.schedules.index')}}" >Schedules</a></li>
<li class="{{Request::is('admin/trips') ? 'active' : ''}}"><a href="{{route('admin.trips.index')}}" >Trips</a></li>
<li class="{{Request::is('admin/bookings') ? 'active' : ''}}"><a href="{{route('admin.bookings.index')}}"><span> Bookings</span></a></li>
<li class="{{Request::is('admin/tickets') ? 'active' : ''}}"><a href="{{route('admin.tickets.index')}}"><span> Tickets</span></a></li>
<li class="{{Request::is('admin/sms/sent') ? 'active' : ''}}"><a href="{{route('admin.sent_sms.index')}}"><span> SMS </span></a></li>

