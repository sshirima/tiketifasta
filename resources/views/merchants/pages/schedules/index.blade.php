@extends('merchants.layouts.master')

@section('title')
    {{ __('merchant_page_buses.page_title_bus_schedules') }}
@endsection

@section('content-head')
    <section class="content-header">
       {{-- <h1>
            {{__('merchant_page_buses.content_header_title_bus_schedules')}}
            <small>{{__('merchant_page_buses.content_header_sub_title_bus_schedules')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('merchant.schedules.index')}}"> {{__('merchant_page_buses.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('merchant_page_buses.navigation_link_bus_schedules')}}</li>
        </ol>--}}
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('merchants.pages.schedules.schedule_panel')
            </div>
            <div class="tab-content">
                {!! $table->render() !!}
            </div>
        </div>
    </section>

@endsection

@section('import_css')

@endsection

@section('import_js')

@endsection