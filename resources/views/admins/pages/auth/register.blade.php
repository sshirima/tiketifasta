@extends('admins.layouts.auth')

@section('title')
    {{ __('page_auth_register.page_title') }}
@endsection

@section('contents')
    <div class="container">
        @include('includes.errors.message')
        <div class="row">
            <div class="col-sm-6 col-md-6 col-md-offset-3">
                <h1 class="text-center login-title">{{__('page_auth_register.form_title_admin')}}</h1>
                <div style="padding-right: 10px" class="login-form">
                    <form class="form-horizontal" role="form" method="post" action="{{route('admin.register')}}" style="padding: 20px">
                        <div class="form-group" >
                            <label for="firstname" class="col-sm-3 control-label">{{__('page_auth_register.label_first_name')}}</label>
                            <div class="col-sm-9">
                                <input type="text" name="firstname" id="firstname" placeholder="{{__('page_auth_register.placeholder_first_name')}}"
                                       class="form-control" autofocus value="{{ old('firstname') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lastname" class="col-sm-3 control-label">{{__('page_auth_register.label_last_name')}}</label>
                            <div class="col-sm-9">
                                <input type="text" name="lastname" id="lastname" placeholder="{{__('page_auth_register.placeholder_last_name')}}"
                                       class="form-control" value="{{ old('lastname') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">{{__('page_auth_register.label_email')}}</label>
                            <div class="col-sm-9">
                                <input type="email" name="email" id="email" placeholder="{{__('page_auth_register.placeholder_email')}}"
                                       class="form-control" value="{{ old('email') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phonenumber" class="col-sm-3 control-label">{{__('page_auth_register.label_phonenumber')}}</label>
                            <div class="col-sm-9">
                                <input type="phonenumber" name="phonenumber" id="phonenumber" placeholder="{{__('page_auth_register.placeholder_phonenumber')}}"
                                       class="form-control" value="{{ old('phonenumber') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-3 control-label">{{__('page_auth_register.label_password')}}</label>
                            <div class="col-sm-9">
                                <input type="password" name="password" id="password" placeholder="{{__('page_auth_register.placeholder_password')}}"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" class="col-sm-3 control-label">{{__('page_auth_register.label_confirm_password')}}</label>
                            <div class="col-sm-9">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       placeholder="{{__('page_auth_register.placeholder_confirm_password')}}" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3">
                                <button type="submit" class="btn btn-primary btn-block">{{__('page_auth_register.label_register')}}</button>
                            </div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
@endsection

