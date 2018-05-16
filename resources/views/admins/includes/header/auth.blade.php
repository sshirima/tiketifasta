@extends('layouts.header')

@section('header-content')
    <div class="col-md-4 col-md-offset-1" style="padding: 15px">

    </div>
    <div class="col-md-3">
        @if (Auth::guard('admin')->check())
            @include('admins.includes.header.components.profile')
        @else
            <div class="pull-right" style="padding-top: 30px">
               <span><a href="#" style="text-decoration:none;">{{__('page_header_profile.help')}}</a></span>
            </div>
        @endif
    </div>
@endsection