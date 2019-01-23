{{--
<li class="{{Request::is('admin/booking-payments') ? 'active' : ''}}"><a href="{{route('admin.booking_payments.index')}}"> Booking Payments </a></li>
--}}
<li class="{{Request::is('admin/merchant-payments*') ? 'active' : ''}}"><a href="{{route('admin.merchant_payments.summary')}}">All disbursements </a></li>
{{--
<li class="{{Request::is('admin/customer-payments/mpesa') ? 'active' : ''}}"><a href="{{route('admin.mpesac2b.index')}}"> Mpesa collections</a></li>
--}}
<li class="{{Request::is('admin/merchants-payments/mpesa') ? 'active' : ''}}"><a href="{{route('admin.mpesab2c.index')}}"> Mpesa disbursements </a></li>
{{--
<li class="{{Request::is('admin/customer-payments/tigopesa') ? 'active' : ''}}"><a href="{{route('admin.tigosecurec2b.index')}}"> Tigopesa collections </a></li>
--}}
<li class="{{Request::is('admin/merchant-payments/tigopesa') ? 'active' : ''}}"><a href="{{route('admin.tigob2c.index')}}"> Tigopesa disbursements</a></li>
<li class="{{Request::is('admin/merchant-scheduled-payment*') ? 'active' : ''}}"><a href="{{route('admin.merchant_schedule_payments.index')}}" >Scheduled disbursements</a></li>
<li class="{{Request::is('admin/merchants-unpaid-transactions') ? 'active' : ''}}"><a href="{{route('admin.merchants_transactions.unpaid')}}" >Unpaid disbursements</a></li>