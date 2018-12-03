@extends('admins.layouts.master')

@section('title')
    Reset merchant account
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            Reset merchant account password
            <small>{{__('merchant_page_staff.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.merchant_accounts.index')}}"> Merchant accounts</a>
            </li>
            <li class="active">Reset password</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box-body">
            <div class="nav-tabs-custom">
                <div class="nav nav-tabs">
                    @include('admins.pages.payment_accounts.merchant_payment_account_options_filed')
                </div>
                <div class="tab-content">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('admin.merchant_accounts.password.send_link') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                                Password reset link will be sent to below mail
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">{{__('page_auth_email.email_address')}}</label>

                            <div class="col-md-6">
                                @if(isset($email))
                                    <input id="email" type="email" class="form-control" name="email"
                                           value="{{ $email}}" required readonly>
                                @else
                                    <input id="email" type="email" class="form-control" name="email"
                                           value="{{ old('email') }}" required>
                                @endif

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{__('page_auth_email.button_password_reset')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

