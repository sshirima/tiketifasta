<li class="{{Request::is('merchant/disbursement/transactions/all') ? 'active' : ''}}"><a href="{{route('merchant.disbursement.transactions.all')}}"> All collections </a></li>
<li class="{{Request::is('merchant/disbursement/transactions/mpesa') ? 'active' : ''}}"><a href="{{route('merchant.disbursement.transactions.mpesa')}}"> Mpesa collections</a></li>
<li class="{{Request::is('merchant/disbursement/transactions/tigopesa') ? 'active' : ''}}"><a href="{{route('merchant.disbursement.transactions.tigopesa')}}"> Tigopesa collections </a></li>
