@extends('merchants.layouts.master-auth')

@section('title')
    {{__('merchant_page_auth_login.page_title')}}
@endsection

@section('content-body')
    @include('includes.errors.message')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{route('merchant.login')}}"><b>{{__('merchant_page_auth_login.merchant')}}</b> {{__('merchant_page_auth_login.panel')}}</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">{{__('merchant_page_auth_login.form_title_merchant')}}</p>

            <form action="{{route('merchant.login')}}" method="post">
                <div class="form-group has-feedback">
                    <input type="email" name="email" class="form-control" placeholder="{{__('merchant_page_auth_login.placeholder_email')}}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password" class="form-control" placeholder="{{__('merchant_page_auth_login.placeholder_password')}}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox">
                            <label class="icheckbox_square-blue" >
                                <input name="remember" type="checkbox" value="remember">
                                {{ __('merchant_page_auth_login.label_remember_me') }}
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">{{__('merchant_page_auth_login.button_sign_in')}}</button>
                    </div>
                    <!-- /.col -->
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
            <!-- /.social-auth-links -->
            <a href="#">{{__('merchant_page_auth_login.forgot_password')}}</a>
        </div>
        <!-- /.login-box-body -->
    </div>
@endsection