<li class="{{Request::is('merchant/bookings') ? 'active' : ''}}"><a href="{{route('merchant.bookings.index')}}" >{{__('merchant_pages.page_booking_panel_show_information')}}</a></li>
{{--
<li class="{{Request::is('merchant/buses/*/edit') ? 'active' : ''}}"><a href="{{route('merchant.buses.edit', $bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_general_settings')}}</a></li>
<li class="{{Request::is('merchant/buses/*/routes/assign') ? 'active' : ''}}"><a href="{{route('merchant.buses.assign_routes', $bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_routes')}}</a></li>
<li class="{{Request::is('merchant/buses/*/price/assign') ? 'active' : ''}}"><a href="{{route('merchant.buses.assign_price', $bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_price')}}</a></li>
<li class="{{Request::is('merchant/buses/*/schedules') ? 'active' : ''}}"><a href="{{route('merchant.buses.schedules', $bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_schedules')}}</a></li>

--}}
