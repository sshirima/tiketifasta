@extends('users.layouts.master')

@section('title')
    {{ __('page_profile_show.page_title') }}
@endsection

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                @include('includes.errors.message')
                <h3 ></h3>
                {!! Form::open(['route' => ['user.profile.update', $user->id], 'method' => 'put']) !!}
                <table class="table">
                    <tbody>
                    <tr><th>{{__('page_profile_edit.label_last_name')}}</th><td>{{ Form::input('text', 'firstname', $user->firstname, ['class' => 'form-control']) }}</td></tr>
                    <tr><th>{{__('page_profile_edit.label_first_name')}}</th><td>{{ Form::input('text', 'lastname', $user->lastname, ['class' => 'form-control']) }}</td></tr>
                    <tr><th>{{__('page_profile_edit.label_email')}}</th><td>{{$user->email}}</td></tr>
                    <tr><th>{{__('page_profile_edit.label_date_created')}}</th>
                        <td>@if(empty($user->created_at)) {{__('page_profile_edit.label_system_created')}} @else {{$user->created_at}} @endif</td>
                    </tr>
                    <tr><th>{{__('page_profile_edit.label_date_updated')}}</th><td>@if(empty($user->updated_at)) Never @else {{$user->updated_at}} @endif</td></tr>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-success">{{__('page_profile_edit.button_save')}}</button>
                {{Form::close()}}
                <br>
                <a href="{{route('user.profile.show')}}"><button class="btn btn-danger">{{__('page_profile_edit.button_cancel')}}</button></a>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
@endsection