<li class="{{Request::is('admin/approvals') ? 'active' : ''}}"><a href="{{route('admin.approvals.index')}}" >{{__('admin_pages.page_approvals_panel_option_pending')}}</a></li>
<li class="{{Request::is('admin/approvals/bus-route*') ? 'active' : ''|| Request::is('admin/inactive/bus-route') ? 'active' : ''}}">
    <a href="{{route('admin.approvals.bus-routes')}}" >{{__('admin_pages.page_approvals_panel_option_bus_routes')}}</a></li>
<li class="{{Request::is('admin/approvals/reassign-schedule*') ? 'active' : ''}}">
    <a href="{{route('admin.approvals.reassigned-schedules')}}" >{{__('admin_pages.page_approvals_panel_option_reassign_bus')}}</a>
</li>