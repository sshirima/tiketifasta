@extends('users.layouts.master')

@section('custom-import')

    <link rel="stylesheet" href="{{ URL::asset('css/seat_charts/jquery.seat-charts.css') }}">
    <script src="{{URL::asset('js/seat_charts/jquery.seat-charts.js')}}"></script>
    <script src="{{ URL::asset('js/merchant/seats_select.js') }}"></script>

@endsection

@section('title')
    {{ __('user_pages.page_title_select_seat') }}
@endsection

@section('contents')
    <section class="content-header col-md-offset-1">
        <h2 >{{__('user_pages.page_select_bus_form_title')}}</h2>

    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        @include('includes.errors.message')
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="container">
                        <div class="col-md-6 col-md-offset-1">
                            <div id="seat-map">
                                <h4 class="front-indicator">Front</h4>
                            </div>
                        </div>
                        <div class="col-md-5">
                            {!! Form::open(['route' => ['booking.details.prepare',$schedule->id, $schedule->busRoute->subRoutes[0]->id], 'method' => 'get']) !!}
                            <div class="booking-details">
                                <h2>Booking Details</h2>
                                <h3> Selected Seats (<span id="counter">0</span>):</h3>
                                <ul id="selected-seats">
                                </ul>Total: <b><span id="total">0</span> (Tshs)</b>
                                <button type="submit" class="checkout-button btn btn-primary" id="submit-seats" disabled>Checkout &raquo;</button>
                                <div id="legend"></div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <script type="text/javascript">
                            var seats = {!! json_encode($seats) !!};
                            var ticketPrices = {!! json_encode($schedule->busRoute->subRoutes[0]->ticketPrice) !!};
                            var seatArrangement = {!! json_encode($schedule->busRoute->bus->busType->seat_arrangement) !!};
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection