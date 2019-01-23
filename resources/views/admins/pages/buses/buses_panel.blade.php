<li class="{{Request::is('admin/buses') ? 'active' : ''}}"><a href="{{route('admin.buses.index')}}" >View buses</a></li>
<li class="{{Request::is('admin/routes*') ? 'active' : ''}}"><a href="{{route('admin.routes.index')}}" >Routes</a></li>
<li class="{{Request::is('admin/bustype*') ? 'active' : ''}}"><a href="{{route('admin.bustype.index')}}" >Bus types</a></li>
<li class="{{Request::is('admin/locations*') ? 'active' : ''}}"><a href="{{route('admin.location.index')}}" >Locations</a></li>

