<li class="{{Request::is('admin/bus-route*') ? 'active' : ''}}"><a href="{{route('admin.bus-routes.index')}}" >{{__('admin_pages.page_bus_route_panel_option_bus_routes')}}</a></li>
<li class="{{Request::is('admin/inactive/bus-route') ? 'active' : ''}}"><a href="{{route('admin.bus-route.inactive.show')}}" >{{__('admin_pages.page_bus_route_panel_option_inactive_bus_routes')}}</a></li>
{{--
<li class="{{Request::is('admin/bus-route/*/timetables') ? 'active' : ''}}"><a href="{{route('admin.bus-route.timetables.show')}}" >
        {{__('admin_pages.page_approve_timetables_panel_option_inactive_timetables')}}</a></li>--}}
