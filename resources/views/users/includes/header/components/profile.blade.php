<div class="dropdown btn btn-default navbar-right" style="margin-top: 20px;">
    <div href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
         aria-expanded="false">
        <i class="fa fa-user-circle" aria-hidden="true"></i> {{__('page_header_profile.logged_in')}} <span class="caret"></span>
    </div>
    <ul class="dropdown-menu">
        <div style="padding-right: 10px;padding-left: 10px"><strong>
                <h4>{{$user->firstname . ', '.$user->lastname}}</h4></strong></div>
        <div style="padding-right: 10px;padding-left: 10px"><i><h6>{{$user->email}}</h6></i></div>
        <li class="divider"></li>
        <li><a href="{{route('user.home')}}">
                <i class="fa fa-home" aria-hidden="true"></i> {{__('page_header_profile.homepage')}}</a></li>
        <li><a href="{{route('user.profile.show')}}">
                <i class="fa fa-eye" aria-hidden="true"></i> {{__('page_header_profile.view_profile')}}</a></li>
        <li><a href="{{route('user.profile.edit')}}">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{__('page_header_profile.edit_profile')}}</a></li>

        <li class="divider"></li>
        <li><a href="{{route('logout')}}">
                <i class="fa fa-sign-out" aria-hidden="true"></i> {{__('page_header_profile.logout')}}</a>
        </li>
        <li><a href="#">
                {{__('page_header_profile.help')}}</a>
        </li>
    </ul>
</div>
