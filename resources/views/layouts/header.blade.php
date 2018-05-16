<div class="container">
    {{--@include('includes.header.brand')--}}
    @yield('header-content')
    {{--<div class="col-md-4 col-md-offset-1" style="padding: 15px">
        @include('includes.header.search-field')
    </div>
    <div class="col-md-1">
        <div style="padding-top: 20px" class="pull-right">
            <span><a href="{{route('seller.dashboard')}}" style="text-decoration:none;"><h4><strong>Merchant</strong></h4></a> </span>
        </div>
    </div>
    <div class="col-md-2">
        @if (Auth::guard('web')->check())
            @include('user.components.headers.components.profile')
        @else
            <div style="padding-top: 30px">
                <span><a href="{{route('login')}}" style="text-decoration:none;"><span class="glyphicon glyphicon-log-in"></span> Login</a> </span><span style="color:white;">|</span>
                <span><a href="{{route('register')}}" style="text-decoration:none;"> Register</a> </span><span style="color:white;">|</span>
                <span><a href="#" style="text-decoration:none;">Help</a></span>
            </div>
        @endif
    </div>--}}
</div>
