@extends('admins.layouts.master')

@section('title')
    Payments | Tigo
@endsection

@section('content-head')

@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                {{--@include('admins.pages.payments.tigoB2C_options_filed')--}}
                @include('admins.pages.payments.payments_disbursement_panel')
            </div>
            <div class="tab-content">
                {!! $table->render() !!}
            </div>
        </div>
    </section>

@endsection