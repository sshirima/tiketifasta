@extends('users.layouts.master')

@section('title')
    {{ __('page_profile_show.page_title') }}
@endsection

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div>@include('flash::message')</div>
                <h3 >{{__('page_profile_edit.form_title')}}</h3>
                <table class="table">
                    <tbody>
                    <tr><th>{{__('page_profile_show.label_first_name')}}</th><td>{{$user->firstname}}</td></tr>
                    <tr><th>{{__('page_profile_show.label_last_name')}}</th><td>{{$user->lastname}}</td></tr>
                    <tr><th>{{__('page_profile_show.label_email')}}</th><td>{{$user->email}}</td></tr>
                    <tr><th>{{__('page_profile_show.label_date_created')}}</th>
                        <td>@if(empty($user->created_at)) {{__('page_profile_show.label_system_created')}} @else {{$user->created_at}} @endif</td>
                    </tr>
                    <tr><th>{{__('page_profile_edit.label_date_updated')}}</th><td>@if(empty($user->updated_at)) {{__('page_profile_show.label_never')}} @else {{$user->updated_at}} @endif</td></tr>
                    </tbody>
                </table>
                <a href="{{route('user.home')}}"><button class="btn btn-primary"> <i class="fa fa-arrow-left" aria-hidden="true"></i>{{__('page_profile_show.button_back_home')}}</button></a>
                <a href="{{route('user.profile.edit')}}"><button class="btn btn-default">{{__('page_profile_show.button_edit_profile')}}</button></a>
                <a href="{{route('password.change')}}"><button class="btn btn-default">{{__('page_profile_show.button_change_pass')}}</button></a>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
@endsection