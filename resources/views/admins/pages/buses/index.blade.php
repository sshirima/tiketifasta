@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_bus_index_title') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            {{__('admin_page_buses.content_header_title')}}
            <small>{{__('admin_page_buses.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.bus-routes.index')}}"> {{__('admin_page_buses.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_buses.navigation_link_view')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box box-primary">
            {{--<div class="box-header">
                <div class="btn btn-success pull-right" data-toggle="modal">
                    <a href="#" style="color: white"><i class="fas fa-plus"></i> {{__('merchant_page_location.panel_nav_tab_new_product')}}</a>
                </div>
            </div>--}}
            <div class="box-body">
                {!! $table->render() !!}
            </div>
        </div>
    </section>
@endsection