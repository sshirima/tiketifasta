<li class="{{Request::is('merchant/collection/transactions/all') ? 'active' : ''}}"><a href="{{route('merchant.collection.transactions.all')}}"> All collections </a></li>
<li class="{{Request::is('merchant/collection/transactions/mpesa') ? 'active' : ''}}"><a href="{{route('merchant.collection.transactions.mpesa')}}"> Mpesa collections</a></li>
<li class="{{Request::is('merchant/collection/transactions/tigopesa') ? 'active' : ''}}"><a href="{{route('merchant.collection.transactions.tigopesa')}}"> Tigopesa collections </a></li>
