<li class="{{Request::is('admin/buses/*/show') ? 'active' : ''}}"><a href="{{route('admin.buses.show', $bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_show_information')}}</a></li>
<li class="{{Request::is('admin/buses/*/edit') ? 'active' : ''}}"><a href="{{route('admin.buses.edit', $bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_general_settings')}}</a></li>
<li class="{{Request::is('admin/buses/*/routes') ? 'active' : ''}}"><a href="{{route('admin.buses.route.show', $bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_routes')}}</a></li>
<li class="{{Request::is('admin/buses/*/schedules') ? 'active' : ''}}"><a href="{{route('admin.buses.schedules', $bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_bus_schedules')}}</a></li>
<li class="{{Request::is('admin/buses/*/authorize') ? 'active' : ''}}"><a href="{{route('admin.buses.authorizes', $bus->id)}}" >{{__('merchant_pages.page_bus_edit_panel_bus_authorizes')}}</a></li>

