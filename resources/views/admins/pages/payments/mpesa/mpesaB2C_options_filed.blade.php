<li class="{{Request::is('admin/merchants-payments/mpesa') ? 'active' : ''}}"><a href="{{route('admin.mpesab2c.index')}}" >View transactions</a></li>
<li class="{{Request::is('admin/merchant-payments/send_cash*') ? 'active' : ''}}"><a href="{{route('admin.mpesab2c.send_cash')}}" >Issue transaction</a></li>
