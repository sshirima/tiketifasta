@extends('admins.layouts.master')

@section('title')
    {{ __('page_home.page_tile_admin') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            {{__('admin_page_merchants.content_header_title')}}
            <small>{{__('admin_page_merchants.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.merchant.index')}}"> {{__('admin_page_merchants.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_merchants.navigation_link_view')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')

    <section class="content container-fluid">
            <div class="box box-primary">
                <div class="box-header">
                    <h6 class="pull-right">
                        <a class="btn btn-primary pull-right"
                           href="{{route('admin.merchant.create')}}">Add merchant</a>
                    </h6>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <div class="nav nav-tabs">
                            @include('admins.pages.payment_accounts.merchant_payment_account_options_filed')
                        </div>
                        <div class="tab-content">
                            @if(count($merchants) <=0)
                                No records
                            @else
                                @include('admins.pages.merchants.table')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection