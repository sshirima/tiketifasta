@extends('admins.layouts.master')

@section('title')
    Merchants payment | Mpesa
@endsection

@section('content-head')
    {{--<section class="content-header">
        <h1>
            {{__('admin_page_bookings.content_header_title')}}
            <small>{{__('admin_page_bookings.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.bookings.index')}}"> {{__('admin_page_bookings.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_bookings.navigation_link_create')}}</li>
        </ol>
    </section>--}}
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.payments.mpesa.mpesaB2C_options_filed')
            </div>
            <div class="tab-content">
                {!! $table->render() !!}
            </div>
        </div>
    </section>
@endsection