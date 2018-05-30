@extends('admins.layouts.master_auth')

@section('title')
    {{ __('page_auth_login.page_title') }}
@endsection

@section('content-body')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{route('admin.login')}}"><b>{{__('admin_page_auth_login.admin')}}</b> {{__('admin_page_auth_login.panel')}}</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">{{ __('page_auth_login.form_title_admin') }}</p>

            <form action="{{route('admin.login')}}" method="post">
                <div class="form-group has-feedback">
                    <input type="email" name="email" class="form-control" placeholder="{{ __('page_auth_login.placeholder_email') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password" class="form-control" placeholder="{{ __('page_auth_login.placeholder_password') }}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox">
                            <label class="icheckbox_square-blue" >
                                <input name="remember" type="checkbox" value="remember">
                                {{ __('page_auth_login.label_remember_me') }}
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('page_auth_login.button_sign_in') }}</button>
                    </div>
                    <!-- /.col -->
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
            <!-- /.social-auth-links -->
            <a href="#">{{ __('page_auth_login.forgot_password') }}</a>
        </div>
        <!-- /.login-box-body -->
    </div>
@endsection