@extends('admins.layouts.master')

@section('title')
    Sent SMS
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
            <li class="active">View</li>
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
                {!! $table->render() !!}
            </div>
        </div>
    </section>
@endsection