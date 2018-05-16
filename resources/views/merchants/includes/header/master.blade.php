@extends('layouts.header')

@section('header-content')
    <div class="col-md-4 col-md-offset-1" style="padding: 15px">

    </div>
    <div class="col-md-1">

    </div>
    <div class="col-md-2">
        @if (Auth::guard('merchant')->check())
            @include('merchants.includes.header.components.profile')
        @else
            <div class="pull-right" style="padding-top: 30px">
                <span><a href="#" style="text-decoration:none;">{{__('page_header_profile.help')}}</a></span>
            </div>
        @endif
    </div>
@endsection