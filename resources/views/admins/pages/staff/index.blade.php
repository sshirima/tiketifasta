@extends('admins.layouts.master')

@section('title')
    Merchants accounts
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            Merchants accounts
            <small>{{__('merchant_page_staff.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.merchant_accounts.index')}}"> {{__('merchant_page_staff.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('merchant_page_staff.navigation_link_view')}}</li>
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
@endsection