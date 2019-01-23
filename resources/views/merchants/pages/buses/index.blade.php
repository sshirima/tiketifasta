@extends('merchants.layouts.master')

@section('title')
    {{ __('merchant_page_buses.page_bus_index_title') }}
@endsection


@section('content-head')
    <section class="content-header">
        {{--<h1>
            {{__('merchant_page_buses.content_header_title')}}
            <small>{{__('merchant_page_buses.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('merchant.buses.index')}}"> {{__('merchant_page_buses.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('merchant_page_buses.navigation_link_view')}}</li>
        </ol>--}}
    </section>
@endsection

@section('content-body')
    @include('flash::message')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('merchants.pages.buses.buses_panel')
            </div>
            <div class="tab-content">
                {!! $table->render() !!}
            </div>
        </div>
    </section>
@endsection