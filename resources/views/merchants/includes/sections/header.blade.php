<header class="main-header">

    <!-- Logo -->
    <a href="{{route('user.home')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>t</b>FASTA</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>tiketi</b>FASTA</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div  class="pull-left text-center"><h4 style="color:white; padding-top: 5px;padding-left: 20px">{{$merchant->merchant->name}}</h4></div>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{asset('adminlte/dist/img/boxed-bg.png')}}" class="user-image" alt="User Image">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{$merchant[\App\Models\Staff::COLUMN_FIRST_NAME].' '.$merchant[\App\Models\Staff::COLUMN_LAST_NAME]}}</span>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{asset('adminlte/dist/img/boxed-bg.png')}}" class="img-circle" alt="User Image">

                            <p>
                                {{$merchant[\App\Models\Staff::COLUMN_FIRST_NAME].' '.$merchant[\App\Models\Staff::COLUMN_LAST_NAME]}}
                                <small>Created at {{date_format(date_create($merchant->created_at), "M, Y")}}</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        {{--<li class="user-body">
                            <div class="row">
                                <div class="col-xs-4 text-center">
                                    <a href="#">Followers</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Sales</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Friends</a>
                                </div>
                            </div>
                            <!-- /.row -->
                        </li>--}}
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{route('merchant.profile.show')}}" class="btn btn-default btn-flat">{{__('merchant_header.profile')}}</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{route('merchant.logout')}}" class="btn btn-default btn-flat">{{__('merchant_header.option_sign_out')}}</a>
                            </div>
                        </li>
                    </ul>
                </li>
                {{--<li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>--}}
            </ul>
        </div>
    </nav>
</header>