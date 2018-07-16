@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_payment_account_index_title') }}
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
            <li class="active">{{__('admin_page_payment_account.navigation_link_view')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-header ">
                Payment accounts
            </div>
            <div class="box-body">
                {!! $table->render() !!}
            </div>
        </div>
    </section>
@endsection