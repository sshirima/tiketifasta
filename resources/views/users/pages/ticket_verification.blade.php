@extends('users.layouts.master_v2')

@section('title')
    Ticket verification
@endsection

@section('content')
    <section class="features-icons bg-light text-center">
        <div class="container">
            @if(isset($booking))
                <div class="row">
                    <div class="col-lg-12">
                        @if(isset($ticket))
                            @if($ticket->status =='CONFIRMED')
                                <div class="lead mb-0 alert alert-success">Your <b>ticket</b> is still valid</div>
                            @endif
                            @if($ticket->status =='EXPIRED')
                                <div class="lead mb-0 alert alert-danger">Sorry, your ticket is <b>expired</b></div>
                            @endif
                            @if($ticket->status =='VALID')
                                <div class="lead mb-0 alert alert-danger">Sorry, your ticket is <b>pending on processing</b></div>
                            @endif
                        @else
                            <div class="lead mb-0 alert alert-warning">Your <b>booking</b> has been created but still
                                pending
                            </div>
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
                                        <span class="lead mb-0">Travelling date: </span><span
                                                class="lead mb-0"><strong>{{$booking->schedule->day->date}}</strong></span>
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
                    </div>
                    @if(isset($ticket))
                        <div class="col-lg-4">
                            <h4 class="mb-5">Ticket information</h4>
                            <div>
                                <span class="lead mb-0">Ticket ref: </span><span
                                        class="lead mb-0"><strong>{{strtoupper($ticket->ticket_ref)}}</strong></span>
                            </div>
                            <div>
                                <span class="lead mb-0 ">Status: </span><span
                                        class="lead mb-0">
                                    @if($ticket->status == 'CONFIRMED')
                                        <span class="alert-success" style="padding-left: 3px;padding-right: 3px"><b>Confirmed</b></span>
                                    @endif
                                        @if($ticket->status == 'VALID')
                                            <span class="alert-warning" style="padding-left: 3px;padding-right: 3px"><b>Pending</b></span>
                                        @endif
                                        @if($ticket->status == 'EXPIRED')
                                            <span class="alert-danger" style="padding-left: 3px;padding-right: 3px"><b>Expired</b></span>
                                        @endif
                                </span>
                            </div>
                            <div>
                                <span class="lead mb-0">Issued at: </span><span
                                        class="lead mb-0"><strong>{{$ticket->created_at}}</strong></span>
                            </div>

                        </div>
                    @else
                        @if($booking->payment == 'mpesa')
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
                        @else
                            <div class="col-lg-4">
                                <h4 class="mb-5">This booking was supposed to be paid by Tigopesa</h4>
                            </div>
                        @endif
                    @endif
                </div>
                <br>
                @if(isset($ticket))
                    <div class="row">
                        @if($ticket->status == 'CONFIRMED')
                            <div class="col-lg-12">
                                <p class="lead mb-0"> Thanks for for booking with us, your ticket reference was sent to
                                    number <b>{{$ticket->booking->phonenumber}}</b></p>
                            </div>
                        @endif

                        @if($ticket->status == 'EXPIRED')
                            <div class="col-lg-12">
                                <p class="lead mb-0"> Your ticket is expired and can not be used</p>
                            </div>
                        @endif

                    </div>
                @else
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="lead mb-0">Please make the payment on the next 5 min otherwise your booking will
                                be
                                cancelled</p>
                        </div>
                    </div>
                @endif
            @elseif(isset($error))
                <div class="alert alert-warning">There is something wrong with the ticket/Payment reference you
                    submitted
                </div>

                <div class="alert alert-danger">Error: {{$error}}</div>

            @else
                <div class="row">
                    <div class="col-lg-12">
                        <div class="lead mb-0 alert alert-info">Confirmation page, please input your ticket reference or
                            payment reference
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-4">
                        <div></div>
                    </div>
                    <div class="col-lg-4">
                        <div>

                            <form role="form" method="post"
                                  action="{{route('user.verify.ticket.submit')}}"
                                  accept-charset="UTF-8">
                                <div class="row ">
                                    <div class="col-sm-12 text-center">
                                        {!! Form::text('reference',old('reference'), ['class' => 'form-control', 'required', 'placeholder'=>'Ticket reference/Payment reference']) !!}
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <strong class="col-sm-4"> </strong>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-primary">Verify</button>
                                    </div>
                                </div>
                                <br>
                                @csrf
                            </form>

                        </div>

                    </div>
                    <div class="col-lg-4">
                        <div></div>
                    </div>

                </div>
            @endif
        </div>
    </section>

@endsection


