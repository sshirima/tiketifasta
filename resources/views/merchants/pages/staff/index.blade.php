@extends('merchants.layouts.master')

@section('title')
    {{ __('page_home.page_tile_merchant') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            {{__('merchant_page_staff.content_header_title')}}
            <small>{{__('merchant_page_staff.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('merchant.staff.index')}}"> {{__('merchant_page_staff.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('merchant_page_staff.navigation_link_view')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box box-success">
            {{--<div class="box-header">
                <div class="btn btn-success pull-right" data-toggle="modal">
                    <a href="#" style="color: white"><i class="fas fa-plus"></i> {{__('merchant_page_staff.panel_nav_tab_new_product')}}</a>
                </div>
            </div>--}}
            <div class="box-body">
                {!! $table->render() !!}
            </div>
        </div>
    </section>

@endsection