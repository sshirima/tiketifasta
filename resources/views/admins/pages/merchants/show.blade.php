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
            <li class="active">{{__('admin_page_merchants.navigation_link_show')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admins.pages.merchants.show_fields')
                    <a href="{!! route('admin.merchant.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </section>
@endsection