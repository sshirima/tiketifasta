@extends('admins.layouts.master')

@section('title')
    Send cash | Tigo
@endsection

@section('content-head')
    {{--<section class="content-header">
        <h1>
            {{__('admin_page_bookings.content_header_title')}}
            <small>{{__('admin_page_bookings.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.bookings.index')}}"> {{__('admin_page_bookings.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_bookings.navigation_link_create')}}</li>
        </ol>
    </section>--}}
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.payments.tigoB2C_options_filed')
            </div>
            <div class="tab-content">
                @if(isset($otpIsSent))

                    @if($otpIsSent)
                        <div class="row">
                            <div class="container col-md-6 col-md-offset-1">

                                @if(isset($otpVerified))
                                    <div class="alert alert-danger">
                                        OTP Verification failed, Please try again<br>
                                        <b>{{$reentryCount}}</b> Re-entry remain
                                    </div>
                                    @else
                                    <div class="alert alert-primary"> Please enter the One Time Password(OTP) sent to your phone for verification</div>
                                @endif

                                <form class="form-horizontal" role="form" method="post" action="{{route('admin.tigob2c.send_cash.verify_otp')}}" accept-charset="UTF-8" style="padding: 20px">
                                    @include('admins.pages.payments.tigoB2C_otp_fields')
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="container col-md-6 col-md-offset-1">
                                <div class="alert alert-danger"> Failed to send One Time Password</div>
                                <div class="alert alert-danger"> Error : {{$error}}</div>
                            </div>
                        </div>
                    @endif

                @elseif(isset($otpVerified) && isset($moneySent))

                    @if($otpVerified)

                        @if($moneySent)
                            <div class="row">
                                <div class="container col-md-11 col-md-offset-1">
                                    <div class="alert alert-success"> <i class="fas fa-check-circle"></i> Fund transfer successful...</div>
                                    <div class="alert alert-default">
                                        Transaction reference: {{$response['REFERENCEID']}}<br>
                                        Transaction ID: {{$response['TXNID']}}<br>
                                        Message: {{$response['MESSAGE']}}<br>

                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="container col-md-6 col-md-offset-1">
                                    <div class="alert alert-warning"> <i class="fas fa-times-circle"></i> Fund transfer failed...</div>
                                    <div class="alert alert-danger">
                                        Transaction reference: {{isset($response['REFERENCEID'])?$response['REFERENCEID']:'null'}}<br>
                                        Message: {{isset($response['MESSAGE'])?$response['MESSAGE']:'null'}}<br>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="row">
                            <div class="container col-md-6 col-md-offset-1">
                                <div class="alert alert-danger"> <i class="fas fa-times-circle"></i> OTP verification failed</div>

                                <form class="form-horizontal" role="form" method="post" action="{{route('admin.tigob2c.send_cash.verify_otp')}}" accept-charset="UTF-8" style="padding: 20px">
                                    @include('admins.pages.payments.tigoB2C_otp_fields')
                                </form>

                            </div>
                        </div>
                    @endif
                @else
                    <div class="row">
                        <div class="container col-md-6 col-md-offset-1">
                            <form class="form-horizontal" role="form" method="get" action="{{route('admin.tigob2c.send_cash.submit')}}" accept-charset="UTF-8" style="padding: 20px">
                                @include('admins.pages.payments.tigoB2C_send_cash_fields')
                            </form>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>

@endsection