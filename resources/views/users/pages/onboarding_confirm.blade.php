@extends('users.layouts.master_v2')

@section('title')
    On boarding
@endsection

@section('content')
    <section class="features-icons bg-light text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    @include('includes.errors.message')
                </div>
            </div>
            @if(isset($ticket))
                @if($ticket->status =='CONFIRMED')
                    <div class="row">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-4">
                            <form role="form" method="post"
                                  action="{{route('merchant.onboarding.confirm')}}"
                                  accept-charset="UTF-8">
                                {{--<div class="row ">
                                    <div class="col-sm-12 text-center">
                                        {!! Form::text('reference',old('reference'), ['class' => 'form-control', 'required', 'placeholder'=>'Ticket reference/Payment reference']) !!}
                                    </div>
                                </div>--}}
                                <div class="row">
                                    <strong class="col-sm-4"> </strong>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-success">Confirm boarding</button>
                                    </div>
                                </div>
                                <br>
                                <input type="text" name="ticket_reference" value="{{$ticket->ticket_ref}}" hidden>
                                @csrf
                            </form>
                        </div>
                        <div class="col-lg-4"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="lead mb-0 alert alert-success">Your <b>ticket</b> is still valid</div>
                        </div>
                    </div>
                    <br>
                @endif
                @if($ticket->status =='EXPIRED')
                    <div class="lead mb-0 alert alert-danger">Sorry, your ticket is <b>expired</b></div>
                @endif
                @if($ticket->status =='VALID')
                    <div class="lead mb-0 alert alert-danger">Sorry, your ticket is <b>pending on processing</b></div>
                @endif

                @if($ticket->status =='BOARDED')
                    <div class="lead mb-0 alert alert-warning">This ticket is currently on <b>boarded</b></div>
                @endif

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
                </div>

            @else
                <div class="lead mb-0 alert alert-warning">
                    <b>Oops there is a problem on retrieving the ticket</b>
                    <p>Ticket not found/Ticket has expired/Today is not the traveling day</p>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <form role="form" method="post"
                              action="{{route('merchant.onboarding.confirm')}}"
                              accept-charset="UTF-8">
                            <div class="row ">
                                <div class="col-sm-12 text-center">
                                    {!! Form::text('ticket_reference',old('ticket_reference'), ['class' => 'form-control', 'required', 'placeholder'=>'Ticket reference']) !!}
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <strong class="col-sm-4"> </strong>
                                <div class="col-sm-4">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                            <br>
                            @csrf
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection


