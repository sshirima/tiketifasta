<li class="{{Request::is('merchant/buses/*/show') ? 'active' : ''}}"><a href="{{route('merchant.buses.show', $bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_show_information')}}</a></li>
<li class="{{Request::is('merchant/buses/*/edit') ? 'active' : ''}}"><a href="{{route('merchant.buses.edit', $bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_general_settings')}}</a></li>
<li class="{{Request::is('merchant/buses/*/routes/assign') ? 'active' : ''}}"><a href="{{route('merchant.buses.assign_routes', $bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_routes')}}</a></li>
<li class="{{Request::is('merchant/buses/*/price/assign') ? 'active' : ''}}"><a href="{{route('merchant.buses.assign_price', $bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_price')}}</a></li>
<li class="{{Request::is('merchant/buses/*/schedules') ? 'active' : ''}}"><a href="{{route('merchant.buses.schedules', $bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_schedules')}}</a></li>
{{--
<li class="{{Request::is('merchant/buses/*/seats') ? 'active' : ''}}"><a href="{{route('merchant.bus.seats',$bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_general_seats')}}</a></li>
<li class="{{Request::is('merchant/buses/*/ticket-prices*') ? 'active' : ''||Request::is('merchant/ticket-price*') ? 'active' : ''}}"><a href="{{route('merchant.ticket_price.index',$bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_general_ticket_prices')}}</a></li>
<li class="{{Request::is('merchant/buses/*/out-of-service*') ? 'active' : ''}}"><a href="{{route('merchant.buses.oos.index',$bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_general_out_of_service')}}</a></li>
--}}
