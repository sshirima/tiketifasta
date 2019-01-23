<li class="{{Request::is('merchant/schedules') ? 'active' : ''}}"><a href="{{route('merchant.schedules.index')}}" >Schedules</a></li>
{{--<li class="{{Request::is('merchant/trips') ? 'active' : ''}}"><a href="{{route('merchant.trips.index')}}" >Trips</a></li>--}}
<li class="{{Request::is('merchant/bookings') ? 'active' : ''}}"><a href="{{route('merchant.bookings.index')}}"><span> Bookings</span></a></li>
<li class="{{Request::is('merchant/tickets') ? 'active' : ''}}"><a href="{{route('merchant.tickets.index')}}"><span> Tickets</span></a></li>

