@extends('users.layouts.auth_master_v2')

@section('title')
    {{ __('page_auth_changepass.page_title') }}
@endsection

@section('contents')
    @include('includes.errors.message')
    <div class="row">
        <div class="col-sm-4 col-md-7 col-md-offset-2">
            <h1 class="text-center login-title">{{__('page_auth_changepass.form_title')}}</h1>
            <div class="panel-body">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form class="form-horizontal" method="POST" action="{{ route('password.update') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                        <label for="new-password" class="col-md-4 control-label">{{__('page_auth_changepass.label_current_password')}}</label>

                        <div class="col-md-6">
                            <input id="current-password" type="password" class="form-control" name="current-password" required>

                            @if ($errors->has('current-password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('current-password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
                        <label for="new-password" class="col-md-4 control-label">{{__('page_auth_changepass.label_new_password')}}</label>

                        <div class="col-md-6">
                            <input id="new-password" type="password" class="form-control" name="new-password" required>

                            @if ($errors->has('new-password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('new-password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="new-password-confirm" class="col-md-4 control-label">{{__('page_auth_changepass.label_new_confirm_password')}}</label>

                        <div class="col-md-6">
                            <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                {{__('page_auth_changepass.button_change_password')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

