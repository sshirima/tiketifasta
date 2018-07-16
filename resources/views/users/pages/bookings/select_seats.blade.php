@extends('users.layouts.master_v2')

@section('title')
    {{ __('user_pages.page_title_select_seat') }}
@endsection

@section('content')

    <section class="showcase">
        @include('flash::message')
        @include('includes.errors.message')
        <div class="container-fluid p-0">
            <div class="row no-gutters">
                    @if(isset($trip->bus))
                    <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                        <h3 class="mb-5">Choose the seat</h3>
                        <div class="pull-right" id="seat-map">
                            <h4 class="front-indicator">Front</h4>
                        </div>
                    </div>
                    <div class="col-lg-5 order-lg-2 my-auto showcase-text text-center">
                        <form role="form" method="get"
                              action="{{route('booking.seat.select', [$trip->bus->id,$trip->schedule_id,$trip->id])}}"
                              accept-charset="UTF-8">
                        <div class="booking-details">
                            <h2>Booking Details</h2>
                            <h3> Selected Seats (<span id="counter">0</span>):</h3>
                            <ul id="selected-seats">
                            </ul>Total: <b><span id="total">0</span> (Tshs)</b>
                            <button type="submit" class="checkout-button btn btn-primary" id="submit-seats" > Checkout &raquo;</button>
                            <div id="legend"></div>
                        </div>
                        </form>
                    </div>

                    @else
                    <div class="col-lg-12 my-auto showcase-text text-center">
                        <div class="alert alert-warning"> Bus not found</div>
                    </div>
                    @endif
            </div>
        </div>
    </section>

@endsection

@section('import_css')
    <link rel="stylesheet" href="{{ URL::asset('css/seat_charts/jquery.seat-charts.css') }}">
@endsection

@section('import_js')
    <script type="text/javascript">
        var seats = {!! json_encode($seats) !!};
        var ticketPrices = {!! json_encode($trip->price) !!};
        var seatArrangement = {!! json_encode($trip->bus->busType->seat_arrangement) !!};
    </script>
    <script src="{{URL::asset('js/seat_charts/jquery.seat-charts.js')}}"></script>
    <script src="{{ URL::asset('js/merchant/seats_select.js') }}"></script>
@endsection