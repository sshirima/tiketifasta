@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_booking_payments_index_title') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            Booking payments
            <small> All payments made to the bookings </small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.booking_payments.index')}}"> Booking payments</a>
            </li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.payments.payments_collections_panel')
            </div>
            <div class="tab-content">
                {!! $table->render() !!}
            </div>
        </div>
    </section>
@endsection