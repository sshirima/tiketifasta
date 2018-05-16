@extends('admins.layouts.master')

@section('title')
    {{ __('page_profile_show.page_title_admin') }}
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
                    <tr><th>{{__('page_profile_show.label_first_name')}}</th><td>{{$admin->firstname}}</td></tr>
                    <tr><th>{{__('page_profile_show.label_last_name')}}</th><td>{{$admin->lastname}}</td></tr>
                    <tr><th>{{__('page_profile_show.label_email')}}</th><td>{{$admin->email}}</td></tr>
                    <tr><th>{{__('page_profile_show.label_date_created')}}</th>
                        <td>@if(empty($admin->created_at)) {{__('page_profile_show.label_system_created')}} @else {{$admin->created_at}} @endif</td>
                    </tr>
                    <tr><th>{{__('page_profile_edit.label_date_updated')}}</th><td>@if(empty($admin->updated_at)) {{__('page_profile_show.label_never')}} @else {{$admin->updated_at}} @endif</td></tr>
                    </tbody>
                </table>
                <a href="{{route('admin.home')}}"><button class="btn btn-primary"> <i class="fa fa-arrow-left" aria-hidden="true"></i>{{__('page_profile_show.button_back_home')}}</button></a>
                <a href="{{route('admin.profile.edit')}}"><button class="btn btn-default">{{__('page_profile_show.button_edit_profile')}}</button></a>
                <a href="{{route('admin.password.change')}}"><button class="btn btn-default">{{__('page_profile_show.button_change_pass')}}</button></a>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
@endsection