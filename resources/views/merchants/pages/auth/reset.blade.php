@extends('merchants.layouts.master-auth')

@section('title')
    {{ __('page_auth_reset.page_title') }}
@endsection

@section('content-body')
    <div class="login-box">
        <div class="login-logo">
            <a href="#">Reset Merchant password</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            {{--<p class="login-box-msg">{{__('merchant_page_auth_login.form_title_merchant')}}</p>--}}

            <form class="form-horizontal" method="POST" action="{{ route('merchant.password.reset.change') }}">
                {{ csrf_field() }}

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">Email</label>

                    <div class="col-md-8">
                        <input id="email" type="email" class="form-control" name="email"
                               value="{{ $email or old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="col-md-4 control-label">New Password</label>

                    <div class="col-md-8">
                        <input id="password" type="password" class="form-control" name="password" required>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <label for="password-confirm" class="col-md-4 control-label">{{__('page_auth_reset.label_confirm_password')}}</label>
                    <div class="col-md-8">
                        <input id="password-confirm" type="password" class="form-control"
                               name="password_confirmation" required>

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            {{__('page_auth_reset.button_reset_password')}}
                        </button>
                    </div>
                </div>
            </form>
            <!-- /.social-auth-links -->
           {{-- <a href="#">{{__('merchant_page_auth_login.forgot_password')}}</a>--}}
        </div>
        <!-- /.login-box-body -->
    </div>
    {{--<div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">{{__('page_auth_reset.panel_title')}}</div>

                    <div class="panel-body">

                    </div>
                </div>
            </div>
        </div>
    </div>--}}
@endsection
