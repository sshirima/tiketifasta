@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_payment_account_create_title') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            Merchant Payments creation form
            <small> </small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.merchant_payment_accounts.index')}}"> MPA</a>
            </li>
            <li class="active">Create</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <div class="nav-tabs-custom">
        <div class="nav nav-tabs">
            @include('admins.pages.payment_accounts.merchant_payment_account_options_filed')
        </div>
        <div class="tab-content">
            <div class="row">
                <div class="container col-md-6 col-md-offset-1">
                    <form class="form-horizontal" role="form" method="post" action="{{route('admin.merchant_payment_accounts.create')}}" accept-charset="UTF-8" style="padding: 20px">
                        @include('admins.pages.payment_accounts.merchant_payment_account_fields')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection