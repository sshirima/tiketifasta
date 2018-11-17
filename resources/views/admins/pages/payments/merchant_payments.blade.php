@extends('admins.layouts.master')

@section('title')
    Merchants Payments
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            Merchants payments
            <small>Payments that were made to the merchants</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.merchant_payments.summary')}}"> Merchant Payments</a>
            </li>
            <li class="active">View</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.payments.merchant_payments_options_fields')
            </div>
            <div class="tab-content">
                @if(isset($summaryReportTable))
                    {!! $summaryReportTable->render() !!}
                @endif

                    @if(isset($merchantReportTable))
                        {!! $merchantReportTable->render() !!}
                    @endif

            </div>
        </div>
    </section>

@endsection