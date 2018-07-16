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
        <div class="box box-primary">
            <div class="box-header ">
                Account information
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="container col-md-6 col-md-offset-1">
                        <form class="form-horizontal" role="form" method="post" action="{{route('admin.payments-accounts.store')}}" accept-charset="UTF-8" style="padding: 20px">
                            @include('admins.pages.payment_accounts.fields')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection