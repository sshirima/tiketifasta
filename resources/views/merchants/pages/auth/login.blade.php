@extends('merchants.layouts.master-auth')

@section('title')
    {{__('Merchant')}} | @lang('Login')
@endsection

@section('content-body')
    @include('includes.errors.message')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{route('merchant.login')}}"><b>{{__('Merchant')}}</b> {{__('PANEL')}}</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">{{__('Merchant login')}}</p>

            <form action="{{route('merchant.login')}}" method="post">
                <div class="form-group has-feedback">
                    <input type="email" name="email" class="form-control" placeholder="{{__('Email address')}}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password" class="form-control" placeholder="{{__('Password')}}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox">
                            <label class="icheckbox_square-blue" >
                                <input name="remember" type="checkbox" value="remember">
                                {{ __('Remember me') }}
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">{{__('Login')}}</button>
                    </div>
                    <!-- /.col -->
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
            <!-- /.social-auth-links -->
            <a href="#">{{__('Forgot your password')}}?</a>
        </div>
        <!-- /.login-box-body -->
    </div>
@endsection