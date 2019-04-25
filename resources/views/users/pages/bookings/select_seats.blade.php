@extends('users.layouts.master_v2')

@section('title')
    {{ __('user_pages.page_title_select_seat') }}
@endsection

@section('content')

    <section class="showcase ">
        @include('flash::message')
        @include('includes.errors.message')
        <div class="container-fluid showcase-text">
            @include('users.pages.bookings.progress_bar')
            <div class="row">
                @if(isset($trip->bus))
                    <div class="col-md-1">

                    </div>
                    <div class="col-md-10">
                        <div class="panel">
                            <div class="panel-heading">
                                @lang('Seat layout for the bus'): <b><i>{{$trip->bus->reg_number}}</i></b>, @lang('Company'):
                               <b><i> {{$trip->bus->merchant->name}}</i></b>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="pull-right cover"
                                             style="background-image: url('/images/layout.png');background-size: 100% 100%">
                                            <div style="padding-top: 70px;padding-bottom: 10px;padding-right: 10px;padding-left: 10px"
                                                 id="seat-map"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <form role="form" method="get"
                                              action="{{route('booking.seat.select', [$trip->bus->id,$trip->schedule_id,$trip->id])}}"
                                              accept-charset="UTF-8">
                                            <h2 class="text-center" >@lang('Trip details')</h2>
                                            <div class="booking-details">
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-6 ">
                                                        <span class="pull-right">
                                                            <b>@lang('From')</b>
                                                        </span>
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <span class="control-input">{{$trip->from->name}}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-6 ">
                                                        <span class="pull-right">
                                                           <b>@lang('To')</b>
                                                        </span>
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <span class="control-input">{{$trip->to->name}}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-6 ">
                                                        <span class="pull-right">
                                                            <b>@lang('Date')</b>
                                                        </span>
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <span class="control-input">{{$schedule->day->date}} </span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-6 ">
                                                        <span class="pull-right">
                                                            <b>@lang('Selected seat')</b>
                                                        </span>
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <span id="counter">@lang('None')</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-6">
                                                        <span class="pull-right">
                                                            <b>@lang('Seat price')</b>
                                                        </span>
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <span id="total">0</span> @lang('Tshs').
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-6"></label>
                                                    <div class="col-sm-6">
                                                        <button type="submit" class="btn btn-info"
                                                                id="submit-seats" disabled>@lang('Proceed')
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                            <ul id="selected-seats"></ul>
                                            <div id="legend"></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
            <div class="col-md-1"></div>

            {{--<div class="col-lg-6 order-lg-1 my-auto showcase-text">
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
                        <button type="submit" class="checkout-button btn btn-primary" id="submit-seats" > Select &raquo;</button>
                        <div id="legend"></div>
                    </div>
                </form>
            </div>--}}

            @else
                <div class="col-md-12 text-center">
                    <div class="alert alert-warning"> Bus not found</div>
                </div>
            @endif
        </div>
    </section>
    {{--<section class="showcase">

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
                            <button type="submit" class="checkout-button btn btn-primary" id="submit-seats" > Select &raquo;</button>
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
    </section>--}}

@endsection

@section('import_css')
    <link rel="stylesheet" href="{{ URL::asset('css/seat_charts/jquery.seat-charts.css') }}">
    <link rel="stylesheet" href="{{asset('css/user_booking.css')}}">
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