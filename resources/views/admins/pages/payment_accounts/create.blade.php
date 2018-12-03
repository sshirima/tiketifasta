@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_payment_account_create_title') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            {{__('admin_page_payment_account.content_header_title')}}
            <small>{{__('admin_page_payment_account.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.buses.index')}}"> {{__('admin_page_payment_account.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_payment_account.navigation_link_create')}}</li>
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
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-6">
                            @if(isset($account))
                                <form class="form-horizontal" role="form" method="post" action="{{route('admin.merchant_payment_accounts.update',$account->id)}}" accept-charset="UTF-8" style="padding: 20px">
                                    <input name="_method" type="hidden" value="PUT">
                                    @include('admins.pages.payment_accounts.fields')
                                </form>
                            @else
                                @if(isset($accountId))
                                    <div class="alert alert-warning"> Account not found with the given Id</div>
                                @else
                                    <form class="form-horizontal" role="form" method="post" action="{{route('admin.merchant_payment_accounts.store')}}" accept-charset="UTF-8" style="padding: 20px">
                                        @include('admins.pages.payment_accounts.fields')
                                    </form>
                                @endif
                            @endif
                        </div>
                        <div class="col-md-5"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection