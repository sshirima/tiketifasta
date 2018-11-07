@extends('admins.layouts.master')

@section('title')
    Bookings
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            Current bookings
            <small>{{__('merchant_page_bookings.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.buses.index')}}"> Bookings </a>
            </li>
            <li class="active">View</li>
        </ol>
    </section>
@endsection

@section('content-body')
    @include('flash::message')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.bookings.booking_panel')
            </div>
            <div class="tab-content">
                {!! $bookingTable->render() !!}
            </div>
        </div>
    </section>
@endsection