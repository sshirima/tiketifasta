@extends('admins.layouts.master')

@section('title')
    Sending SMS
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.sent_sms.index')}}"> Sent sms</a>
            </li>
            <li class="active">Sending SMS</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.sms.send_sms_option_fields')
            </div>
            <div class="tab-content">
                @if(isset($sentStatus))
                    @if($sentStatus)
                        <div class="row">
                            <div class="container col-md-6 col-md-offset-1">
                                <div class="alert alert-success"> Message has been sent to number <b>{{$phoneNumber}}</b></div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="container col-md-6 col-md-offset-1">
                                <div class="alert alert-danger"> Failed to send SMS</div>
                                <div class="alert alert-danger"> Error : {{$error}}</div>
                            </div>
                        </div>
                    @endif
                    @else
                    <div class="row">
                        <div class="container col-md-6 col-md-offset-1">
                            <form class="form-horizontal" role="form" method="post" action="{{route('admin.sms.send.submit')}}" accept-charset="UTF-8" style="padding: 20px">
                                @include('admins.pages.sms.send_sms_fields')
                            </form>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>
@endsection