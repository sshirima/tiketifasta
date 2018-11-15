@extends('users.layouts.master_v2')

@section('title')
    Confirmation
@endsection

@section('content')
    <section class="features-icons bg-light text-center">
        <div class="container">
            @if(isset($booking))
                <div class="row">
                    <div class="col-lg-12">
                        @if(isset($ticket))
                            <div class="lead mb-0 alert alert-success">Your <b>ticket</b> has been created!</div>
                        @else
                            <div class="lead mb-0 alert alert-success">Your <b>booking</b> has been created!</div>
                        @endif
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-4">
                        <div>
                            <div>
                                <h4 class="mb-5">Booking information</h4>
                                <div>
                                    <span class="lead mb-0">First name: </span><span
                                            class="lead mb-0"><strong>{{$booking->firstname}}</strong></span>
                                </div>
                                <div>
                                    <span class="lead mb-0">Last name: </span><span
                                            class="lead mb-0"><strong>{{$booking->lastname}}</strong></span>
                                </div>
                                <div>
                                    <span class="lead mb-0">Phone number: </span><span
                                            class="lead mb-0"><strong>{{$booking->phonenumber}}</strong></span>
                                </div>
                                <div>
                                    <span class="lead mb-0">Email: </span><span
                                            class="lead mb-0"><strong>{{$booking->email}}</strong></span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        @if(isset($trip))
                            <div>
                                <div>
                                    <h4 class="mb-5">Trip information</h4>
                                    <div>
                                        <span class="lead mb-0">Date: </span><span
                                                class="lead mb-0"><strong>{{$trip->date}}</strong></span>
                                    </div>
                                    <div>
                                        <span class="lead mb-0">Depart time: </span><span
                                                class="lead mb-0"><strong>{{$trip->depart_time}}</strong></span>
                                    </div>
                                    <div>
                                        <span class="lead mb-0">Arrival time: </span><span
                                                class="lead mb-0"><strong>{{$trip->arrival_time}}</strong></span>
                                    </div>
                                    <div>
                                        <span class="lead mb-0">Company: </span><span
                                                class="lead mb-0"><strong>{{$trip->bus->merchant->name}}</strong>  <strong> {{' ('.$trip->bus->reg_number.') '}} </strong></span>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        <span class="lead mb-0">Seat name: </span><span
                                                class="lead mb-0"><strong>{{$trip->bus->seat_name}}</strong></span>
                                    </div>
                                    <div>
                                        <span class="lead mb-0">Ticket price: </span><span
                                                class="lead mb-0"><strong>{{$trip->price}}</strong>  <strong> {{' (Tshs) '}} </strong></span>
                                    </div>
                                </div>
                            </div>
                        @endif
                            @if(isset($transaction))
                                <div>
                                    <div>
                                        <h4 class="mb-5">Trip information</h4>
                                        <div>
                                            <span class="lead mb-0">Date: </span><span
                                                    class="lead mb-0"><strong>{{$transaction->bookingPayment->schedule->day->date}}</strong></span>
                                        </div>
                                        <div>
                                            <span class="lead mb-0">Depart time: </span><span
                                                    class="lead mb-0"><strong>{{$transaction->bookingPayment->booking->trip->depart_time}}</strong></span>
                                        </div>
                                        <div>
                                            <span class="lead mb-0">Arrival time: </span><span
                                                    class="lead mb-0"><strong>{{$transaction->bookingPayment->booking->trip->arrival_time}}</strong></span>
                                        </div>
                                        <div>
                                            <span class="lead mb-0">Company: </span><span
                                                    class="lead mb-0"><strong>{{$trip->bus->merchant->name}}</strong>  <strong> {{' ('.$transaction->bookingPayment->booking->trip->bus->reg_number.') '}} </strong></span>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <span class="lead mb-0">Seat name: </span><span
                                                    class="lead mb-0"><strong>{{$transaction->bookingPayment->booking->seat->seat_name}}</strong></span>
                                        </div>
                                        <div>
                                            <span class="lead mb-0">Ticket price: </span><span
                                                    class="lead mb-0"><strong>{{$transaction->bookingPayment->booking->trip->price}}</strong>  <strong> {{' (Tshs) '}} </strong></span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                    </div>
                    @if(isset($ticket))
                        <div class="col-lg-4">
                            <h4 class="mb-5">Ticket information</h4>
                            <div>
                                <span class="lead mb-0">Ticket ref: </span><span
                                        class="lead mb-0"><strong>{{strtoupper($ticket->ticket_ref)}}</strong></span>
                            </div>
                            <div>
                                <span class="lead mb-0">Ticket: </span><span
                                        class="lead mb-0"><strong>{{$ticket->status}}</strong></span>
                            </div>
                            <div>
                                <span class="lead mb-0">Issued at: </span><span
                                        class="lead mb-0"><strong>{{$ticket->created_at}}</strong></span>
                            </div>

                        </div>
                    @else
                        <div class="col-lg-4">
                            <h4 class="mb-5">Payment information</h4>
                            <div class="text-info">1. Dial <b>*150*00#</b></div>
                            <div class="text-info">2. Go to pay with mpesa</div>
                            <div class="text-info">3. Enter company number: <b>{{ env('MPESA_SPID') }}</b>
                                ({{env('MPESA_ACCOUNT_NAME')}})
                            </div>
                            <div class="text-info">4. Enter payment number: <b>{{$bookingPayment->payment_ref}}</b>
                            </div>
                            <div class="text-info">5. Enter amount: <b>{{$bookingPayment->amount}}</b></div>
                            <div class="text-info">6. Then submit:</div>
                        </div>
                    @endif
                </div>
                <br>
                @if(isset($ticket))
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="lead mb-0"> Thanks for for booking with us, your ticket reference been sent to number <b>{{$ticket->booking->phonenumber}}</b></p>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="lead mb-0">Please make the payment on the next 5 min otherwise your booking will be
                                cancelled</p>
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


