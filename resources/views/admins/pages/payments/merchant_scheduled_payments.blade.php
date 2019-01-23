@extends('admins.layouts.master')

@section('title')
    Scheduled payments
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            Merchants scheduled payments
            <small>Payments that were made to the merchants</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.merchant_schedule_payments.index')}}"> Payments</a>
            </li>
            <li class="active">View</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.payments.payments_disbursement_panel')
            </div>
            <div class="tab-content">
                @if(isset($merchantPaymentTable))
                    {!! $merchantPaymentTable->render() !!}
                @endif
                    @if(isset($bookingPaymentTable))
                        {!! $bookingPaymentTable->render() !!}
                    @endif
            </div>
        </div>
    </section>

@endsection