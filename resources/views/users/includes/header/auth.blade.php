@extends('layouts.header')

@section('header-content')
    <div class="col-md-4 col-md-offset-1" style="padding: 15px">

    </div>
    <div class="col-md-1">
        <div style="padding-top: 20px" class="pull-right">

        </div>
    </div>
    <div class="col-md-2">
        @if (Auth::guard('web')->check())
            @include('users.includes.header.components.profile')
        @else
            <div style="padding-top: 30px">
                <span><a href="{{route('login')}}" style="text-decoration:none;"><span class="glyphicon glyphicon-log-in"></span> {{__('page_header_profile.login')}}</a> </span><span style="color:white;">|</span>
                <span><a href="{{route('register')}}" style="text-decoration:none;"> {{__('page_header_profile.register')}}</a> </span><span style="color:white;">|</span>
                <span><a href="#" style="text-decoration:none;">{{__('page_header_profile.help')}}</a></span>
            </div>
        @endif
    </div>
@endsection