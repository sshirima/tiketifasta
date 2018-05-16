@extends('merchants.layouts.master')

@section('custom-import')
    <link rel="stylesheet" href="{{ URL::asset('css/seat_charts/jquery.seat-charts.css') }}">
    <script src="{{URL::asset('js/seat_charts/jquery.seat-charts.js')}}"></script>
    <script src="{{ URL::asset('js/merchant/seats_select.js') }}"></script>
@endsection

@section('title')
    {{ __('merchant_pages.page_title_bus_type_index') }}
@endsection

@section('panel_heading')
    @include('merchants.pages.bus_types.bustype_panel')
@endsection

@section('panel_body')
    @include('flash::message')
    <div class="row">
        <div class="container">
            <div class="col-md-4">
                <div id="seat-map">
                    <h4 class="front-indicator">Front</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="booking-details">
                    <h2>Booking Details</h2>
                    <h3> Selected Seats (<span id="counter">0</span>):</h3>
                    <ul id="selected-seats">
                    </ul>Total: <b>$<span id="total">0</span></b>
                    <button class="checkout-button">Checkout &raquo;</button>
                    <div id="legend"></div>
                </div>
            </div>
        </div>
    </div>


@endsection