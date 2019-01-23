<li class="{{Request::is('admin/collection/transactions/all') ? 'active' : ''}}"><a href="{{route('admin.booking_payments.index')}}"> All collections </a></li>
<li class="{{Request::is('admin/collection/transactions/mpesa') ? 'active' : ''}}"><a href="{{route('admin.mpesac2b.index')}}"> Mpesa collections</a></li>
<li class="{{Request::is('admin/collection/transactions/tigopesa') ? 'active' : ''}}"><a href="{{route('admin.tigosecurec2b.index')}}"> Tigopesa collections </a></li>
