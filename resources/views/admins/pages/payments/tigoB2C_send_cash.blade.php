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
                                <div class="alert alert-primary"> Please enter the One Time Password(OTP) sent to your phone for verification</div>
                                @if(isset($error))
                                    <div class="alert alert-danger"> {{$error}}</div>
                                @endif
                                <form class="form-horizontal" role="form" method="post" action="{{route('admin.tigob2c.send_cash.verify_otp')}}" accept-charset="UTF-8" style="padding: 20px">
                                    <div class="form-group">
                                        {!! Form::label('otp', 'OTP:', ['class'=>'col-sm-5 control-label', 'for'=>'otp']) !!}
                                        <div class="col-sm-7">
                                            {!! Form::number('otp', null, ['class' => 'form-control', 'placeholder'=>'OTP']) !!}
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group">
                                        <div class="col-sm-7 col-md-offset-5">
                                            {!! Form::submit(__('admin_pages.page_payment_account_fields_verify'), ['class' => 'btn btn-primary']) !!}
                                            <a href="{!! route('admin.tigob2c.send_cash') !!}" class="btn btn-default">{{__('admin_pages.page_locations_fields_button_cancel')}}</a>
                                        </div>
                                    </div>
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
                    OTP Verified!!!
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