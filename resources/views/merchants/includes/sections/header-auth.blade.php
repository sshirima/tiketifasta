<header class="main-header" style="background-color:white">

    <!-- Logo -->
    <a href="/" class="logo">
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>{{__('application.gulio')}}</b>{{__('application.poa')}}</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li ><a href="{{route('merchant.register')}}"><i class="fas fa-user-plus"></i> {{__('auth.register')}}</a></li>
                <li><a href="{{route('merchant.login')}}"><i class="fas fa-sign-in-alt"></i> {{__('auth.login')}}</a></li>
            </ul>
        </div>
    </nav>
</header>