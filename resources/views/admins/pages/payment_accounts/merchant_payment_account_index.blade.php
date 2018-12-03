@extends('admins.layouts.master')

@section('title')
    Merchant Payment Accounts
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            Merchant Payments accounts
            <small>These are account used to pay the merchants</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.merchant_payment_accounts.index')}}"> MPA</a>
            </li>
            <li class="active">View</li>
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
                    {!! $table->render() !!}
                </div>
            </div>
        </div>
    </section>
    {{--<section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-header ">
                Accounts
            </div>
            <div class="box-body">
                {!! $accountTable->render() !!}
            </div>
        </div>
    </section>--}}
@endsection