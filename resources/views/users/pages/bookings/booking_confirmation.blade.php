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
                    <div class="lead mb-0 alert alert-success">Your booking has been created!</div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-4">
                    <div >
                        <div>
                            <h4 class="mb-5">Booking information</h4>
                                <div>
                                    <span class="lead mb-0">First name: </span ><span class="lead mb-0"><strong>{{$booking->firstname}}</strong></span>
                                </div>
                                <div>
                                    <span class="lead mb-0">Last name: </span><span class="lead mb-0"><strong >{{$booking->lastname}}</strong></span>
                                </div>
                                <div>
                                    <span class="lead mb-0">Phone number: </span><span class="lead mb-0"><strong>{{$booking->phonenumber}}</strong></span>
                                </div>
                                <div>
                                    <span class="lead mb-0">Email: </span><span class="lead mb-0"><strong>{{$booking->email}}</strong></span>
                                </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div >
                        <div>
                            <h4 class="mb-5">Trip information</h4>
                            <div>
                                <span class="lead mb-0">Date: </span><span class="lead mb-0"><strong>{{$trip->date}}</strong></span>
                            </div>
                            <div>
                                <span class="lead mb-0">Depart time: </span><span class="lead mb-0"><strong>{{$trip->depart_time}}</strong></span>
                            </div>
                            <div>
                                <span class="lead mb-0">Arrival time: </span><span class="lead mb-0"><strong>{{$trip->arrival_time}}</strong></span>
                            </div>
                            <div>
                                <span class="lead mb-0">Company: </span><span class="lead mb-0"><strong>{{$trip->bus->merchant->name}}</strong>  <strong> {{' ('.$trip->bus->reg_number.') '}} </strong></span>
                            </div>
                        </div>
                        <div>
                            <div>
                                <span class="lead mb-0">Seat name: </span><span class="lead mb-0"><strong>{{$trip->bus->seat_name}}</strong></span>
                            </div>
                            <div>
                                <span class="lead mb-0">Ticket price: </span><span class="lead mb-0"><strong>{{$trip->price}}</strong>  <strong> {{' (Tshs) '}} </strong></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <h4 class="mb-5">Payment information</h4>
                    <div class="text-info">1. Dial <b>*150*00#</b></div>
                    <div class="text-info">2. Go to pay with mpesa</div>
                    <div class="text-info">3. Enter company number: <b>{{ env('MPESA_SPID') }}</b> </div>
                    <div class="text-info">4. Enter payment number: <b>{{$bookingPayment->payment_ref}}</b> </div>
                    <div class="text-info">5. Enter amount: <b>{{$bookingPayment->amount}}</b></div>
                    <div class="text-info">6. Then submit: </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12">
                    <p class="lead mb-0" >Please make the payment on the next 5 min otherwise your booking will be cancelled</p>
                </div>
            </div>
            @else
                @if(isset($error))
                    <div class="alert alert-danger">Something went wrong, please try again</div>

                    <div class="alert alert-danger">{{$error}}</div>
                    @else
                    <div class="alert alert-warning">This seat has been booked, please try another seat</div>
                @endif
            @endif
        </div>
    </section>

@endsection


