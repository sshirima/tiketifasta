@extends('users.layouts.master')

@section('title')
    {{ __('user_pages.page_title_booking_details') }}
@endsection

@section('contents')
    <section class="content-header ">
        <h2 >{{__('user_pages.page_booking_details_form_title')}}</h2>

    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        @include('includes.errors.message')
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="container col-md-6 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>
                                    Journey
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="container">
                                        <div >Date: </div>
                                        <div >
                                            <strong>{{$schedule->day->date}}}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="container">
                                    <div > Depart from: </div>
                                    <div >
                                        <strong>{{$schedule->busRoute->subRoutes[0]->sourceLocation->name}}}</strong> on <strong> {{$schedule->busRoute->subRoutes[0]->depart_time}} </strong>
                                    </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="container">
                                    <div > Arrive  at: </div>
                                    <div >
                                        <strong>{{$schedule->busRoute->subRoutes[0]->destinationLocation->name}}}</strong> on <strong> {{$schedule->busRoute->subRoutes[0]->arrival_time}}} </strong>
                                    </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="container">
                                    <div > Bus info: </div>
                                    <div >
                                        <strong>{{$schedule->busRoute->bus->merchant->name}}}</strong>  <strong> {{' ('.$schedule->busRoute->bus->reg_number.') '}} </strong>
                                    </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="container col-md-4">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>
                                    Tickets
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="container">
                                    <div > Ticket type: </div>
                                    <div >
                                        <strong>{{$schedule->busRoute->subRoutes[0]->ticketPrice->ticket_type}}</strong>
                                    </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="container">
                                    <div > Seat name: </div>
                                    <div >
                                        <strong>{{$schedule->seat}}</strong>
                                    </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="container">
                                    <div > Ticket price: </div>
                                    <div >
                                        <strong>{{$schedule->busRoute->subRoutes[0]->ticketPrice->price}}</strong>  <strong> {{' (Tshs) '}} </strong>
                                    </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="row">
                    {!! Form::open(['route' => ['booking.details.store',$schedule->id, $schedule->busRoute->subRoutes[0]->id], 'method' => 'post']) !!}
                    <div class="container col-md-6 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>
                                    Personal information
                                </h4>
                            </div>
                            <div class="panel-body">
                                    @include('users.pages.bookings.booking_details_fields')
                            </div>

                        </div>
                    </div>
                    <div class="container col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>
                                    Payments
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="container">
                                        <div > Ticket price: </div>
                                        <div >
                                            <strong>1 ticket</strong><strong>{{$schedule->busRoute->subRoutes[0]->ticketPrice->price}}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="container">
                                        <div > Tax: </div>
                                        <div >
                                            <strong>0 (Tsh)</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="container">
                                        <div > Total: </div>
                                        <div >
                                            <strong>{{$schedule->busRoute->subRoutes[0]->ticketPrice->price+0 .' (Tshs)'}}</strong>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="row">
                            <div class="container">
                                <div class="checkbox">
                                    <label>
                                        <input name="agree_terms" type="checkbox">
                                        <a href="#">{{__('page_auth_register.label_terms_conditions')}}</a>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit information</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection