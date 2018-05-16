@extends('admins.layouts.auth')

@section('title')
    {{ __('page_auth_login.page_title') }}
@endsection

@section('contents')
    @include('includes.errors.message')
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">{{ __('page_auth_login.form_title_admin') }}</h1>
            <div class="login-form">
                <img class="profile-img"
                     src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
                     alt="">

                <form class="form-signin" method="post" action="{{route('admin.login')}}">
                    <br>
                    <input name="email" type="text" class="form-control" placeholder="{{ __('page_auth_login.placeholder_email') }}" required autofocus>
                    <br>
                    <input name="password" type="password" class="form-control" placeholder="{{ __('page_auth_login.placeholder_password') }}" required><br>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">
                        {{ __('page_auth_login.button_sign_in') }}
                    </button>
                    <label class="checkbox pull-left">
                        <input name="remember" type="checkbox" value="remember">
                        {{ __('page_auth_login.label_remember_me') }}
                    </label>
                    <a href="{{route('admin.password.request')}}" class="pull-right need-help">{{ __('page_auth_login.forgot_password') }}</a><span class="clearfix"></span>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
            </div>
        <br><br>
        </div>
    </div>
@endsection

