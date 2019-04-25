@extends('users.layouts.master_v2')

@section('title')
    @lang('Booking confirmation')
@endsection

@section('content')
    <section class="features-icons bg-light text-center">
        @include('users.pages.bookings.progress_bar')
        <div class="container">
            @if(isset($booking))
                <div class="row">
                    <div class="col-lg-12">
                        @if(isset($ticket))
                            <div class="lead mb-0 alert alert-success">@lang('Your ticket has been created')!</div>
                        @else
                            <div class="lead mb-0 alert alert-success">@lang('Your booking has been created')!</div>
                        @endif
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-4">
                        <div>
                            <div>
                                <h4 class="mb-5">@lang('Customer information')</h4>
                                <div>
                                    <span class="lead mb-0">@lang('First name'): </span><span
                                            class="lead mb-0"><strong>{{$booking->firstname}}</strong></span>
                                </div>
                                <div>
                                    <span class="lead mb-0">@lang('Last name'): </span><span
                                            class="lead mb-0"><strong>{{$booking->lastname}}</strong></span>
                                </div>
                                <div>
                                    <span class="lead mb-0">@lang('Phone number'): </span><span
                                            class="lead mb-0"><strong>{{$booking->phonenumber}}</strong></span>
                                </div>
                                @if(isset($booking->email))
                                    <div>
                                        <span class="lead mb-0">@lang('Email address'): </span><span
                                                class="lead mb-0"><strong>{{$booking->email}}</strong></span>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        @if(isset($trip))
                            <div>
                                <div>
                                    <h4 class="mb-5">@lang('Trip details')</h4>
                                    <div>
                                        <span class="lead mb-0">@lang('Date'): </span><span
                                                class="lead mb-0"><strong>{{$trip->date}}</strong></span>
                                    </div>
                                    <div>
                                        <span class="lead mb-0">@lang('Departures'): </span><span
                                                class="lead mb-0"><strong>{{$trip->depart_time}}</strong></span>
                                    </div>
                                    <div>
                                        <span class="lead mb-0">@lang('Arrival'): </span><span
                                                class="lead mb-0"><strong>{{$trip->arrival_time}}</strong></span>
                                    </div>
                                    <div>
                                        <span class="lead mb-0">@lang('Company'): </span><span
                                                class="lead mb-0"><strong>{{$trip->bus->merchant->name}}</strong>  <strong> {{' ('.$trip->bus->reg_number.') '}} </strong></span>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        <span class="lead mb-0">@lang('Seat number'): </span><span
                                                class="lead mb-0"><strong>{{$trip->bus->seat_name}}</strong></span>
                                    </div>
                                    <div>
                                        <span class="lead mb-0">@lang('Ticket price'): </span><span
                                                class="lead mb-0"><strong>{{$trip->price}}</strong>  <strong> {{'Tshs'}} </strong></span>
                                    </div>
                                </div>
                            </div>
                        @endif
                            @if(isset($transaction))
                                <div>
                                    <div>
                                        <h4 class="mb-5">>@lang('Trip details')</h4>
                                        <div>
                                            <span class="lead mb-0"></span><span
                                                    class="lead mb-0"><strong>{{$transaction->bookingPayment->booking->trip->from->name}}</strong></span> to
                                            <span
                                                    class="lead mb-0"><strong>{{$transaction->bookingPayment->booking->trip->to->name}}</strong></span>
                                        </div>
                                        <div>
                                            <span class="lead mb-0">@lang('Date'): </span><span
                                                    class="lead mb-0"><strong>{{$transaction->bookingPayment->booking->schedule->day->date}}</strong></span>
                                        </div>
                                        <div>
                                            <span class="lead mb-0">@lang('Departures'): </span><span
                                                    class="lead mb-0"><strong>{{$transaction->bookingPayment->booking->trip->depart_time}}</strong></span>
                                        </div>
                                        <div>
                                            <span class="lead mb-0">@lang('Arrival'): </span><span
                                                    class="lead mb-0"><strong>{{$transaction->bookingPayment->booking->trip->arrival_time}}</strong></span>
                                        </div>
                                        <div>
                                            <span class="lead mb-0">@lang('Company'): </span><span
                                                    class="lead mb-0"><strong>{{$transaction->bookingPayment->booking->trip->bus->merchant->name}}</strong>  <strong> {{' ('.$transaction->bookingPayment->booking->trip->bus->reg_number.') '}} </strong></span>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <span class="lead mb-0">@lang('Seat number'): </span><span
                                                    class="lead mb-0"><strong>{{$transaction->bookingPayment->booking->seat->seat_name}}</strong></span>
                                        </div>
                                        <div>
                                            <span class="lead mb-0">@lang('Ticket price'): </span><span
                                                    class="lead mb-0"><strong>{{$transaction->bookingPayment->booking->trip->price}}</strong>  <strong> {{' (Tshs) '}} </strong></span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                    </div>
                    @if(isset($ticket))
                        <div class="col-lg-4">
                            <h4 class="mb-5">@lang('Ticket information')</h4>
                            <div>
                                <span class="lead mb-0">@lang('Ticket reference'): </span><span
                                        class="lead mb-0"><strong>{{strtoupper($ticket->ticket_ref)}}</strong></span>
                            </div>
                            <div>
                                <span class="lead mb-0 ">@lang('Ticket status'): </span><span
                                        class="lead mb-0">
                                    @if($ticket->status == 'CONFIRMED')
                                        <span class="alert-success" style="padding-left: 3px;padding-right: 3px"><b>@lang('Confirmed')</b></span>
                                    @endif
                                    @if($ticket->status == 'VALID')
                                        <span class="alert-warning" style="padding-left: 3px;padding-right: 3px"><b>@lang('Pending')</b></span>
                                    @endif
                                    @if($ticket->status == 'EXPIRED')
                                        <span class="alert-danger" style="padding-left: 3px;padding-right: 3px"><b>@lang('Expired')</b></span>
                                    @endif
                                </span>
                            </div>
                            <div>
                                <span class="lead mb-0">@lang('Issued at'): </span><span
                                        class="lead mb-0"><strong>{{$ticket->created_at}}</strong></span>
                            </div>

                        </div>
                    @else
                        <div class="col-lg-4">
                            <h4 class="mb-5">@lang('Payment information')</h4>
                            <div class="text-info">1. @lang('Dial') <b>*150*00#</b></div>
                            <div class="text-info">2. @lang('Go to pay with mpesa')</div>
                            <div class="text-info">3. @lang('Enter company number'): <b>{{ env('MPESA_SPID') }}</b>
                                ({{env('MPESA_ACCOUNT_NAME')}})
                            </div>
                            <div class="text-info">4. @lang('Enter payment number'): <b>{{$bookingPayment->payment_ref}}</b>
                            </div>
                            <div class="text-info">5. @lang('Enter amount'): <b>{{$bookingPayment->amount}}</b></div>
                            <div class="text-info">6. @lang('Then submit'):</div>
                        </div>
                    @endif
                </div>
                <br>
                @if(isset($ticket))
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="lead mb-0"> @lang('Thanks for for booking with us, your ticket reference been sent to number') <b>{{$ticket->booking->phonenumber}}</b></p>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="lead mb-0">@lang('Please make the payment on the next 5 min otherwise your booking will be cancelled')</p>
                        </div>
                    </div>
                @endif
            @else

                @if(isset($error))
                    <div class="alert alert-warning">Something went wrong, please try again</div>

                    <div class="alert alert-danger">{{$error}}</div>

                @else
                    <div class="alert alert-warning">This seat has been booked, please try another seat</div>
                @endif

            @endif
        </div>
    </section>

@endsection
@section('import_css')
    <link rel="stylesheet" href="{{asset('css/user_booking.css')}}">
@endsection


