@extends('users.layouts.master_v2')

@section('title')
    {{ __('user_pages.page_title_booking_details') }}
@endsection


@section('content')
    <section class="showcase">
        <div class="container-fluid showcase-text">
            @include('users.pages.bookings.progress_bar')
            <div class="row">
                <div class="col-md-7">
                    <div class="panel">
                        <div class="panel-heading">
                            @lang('Personal information')
                        </div>
                        <div class="panel-body">
                            @include('users.pages.bookings.booking_details_fields')
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="panel">
                        <div class="panel-heading">
                            @lang('Trip details')
                        </div>
                        <div class="panel-body">
                            <div class="row form-group">
                                <div  class="control-label col-sm-4 pull-right"><strong class="pull-right">@lang('From')</strong></div>
                                <div class="col-sm-8">
                                    {{$trip->from}}
                                </div>
                            </div>
                            <div class="row form-group">
                                <div  class="control-label col-sm-4 pull-right"><strong class="pull-right">@lang('To')</strong></div>
                                <div class="col-sm-8">
                                    {{$trip->to}}
                                </div>
                            </div>
                            <div class="row form-group">
                                <div  class="control-label col-sm-4 pull-right"><strong class="pull-right">@lang('Date')</strong></div>
                                <div class="col-sm-8">
                                    {{$trip->date}}
                                </div>
                            </div>

                            <div class="row  form-group">
                                <div  class="control-label col-sm-4 pull-right"><strong class="pull-right">@lang('Departures')</strong></div>
                                <div class="col-sm-8">
                                    {{$trip->depart_time}}
                                </div>
                            </div>
                            <div class="row  form-group">
                                <div  class="control-label col-sm-4 pull-right"><strong class="pull-right">@lang('Arrival')</strong></div>
                                <div class="col-sm-8">
                                    {{$trip->arrival_time}}
                                </div>
                            </div>
                            <div class="row  form-group">
                                <div  class="control-label col-sm-4 pull-right"><strong class="pull-right">@lang('Bus Registration')</strong></div>
                                <div class="col-sm-8">
                                    {{$trip->bus->reg_number}}
                                </div>
                            </div>
                            <div class="row  form-group">
                                <div  class="control-label col-sm-4 pull-right"><strong class="pull-right">@lang('Company')</strong></div>
                                <div class="col-sm-8">
                                    {{$trip->bus->merchant->name}}
                                </div>
                            </div>
                            <div class="row  form-group">
                                <div  class="control-label col-sm-4 pull-right"><strong class="pull-right">@lang('Seat number')</strong></div>
                                <div class="col-sm-8">
                                    {{$trip->bus->seat_name}}
                                </div>
                            </div>
                            <div class="row  form-group">
                                <div  class="control-label col-sm-4 pull-right"><strong class="pull-right">@lang('Price')</strong></div>
                                <div class="col-sm-8">
                                    {{$trip->price}} @lang('Tshs').
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--<h3 class="mb-5">Journey details</h3>
                    <div>
                        <span>Date: </span><span><strong>{{$trip->date}}</strong></span>
                    </div>
                    <div>
                        <span>Depart time: </span><span><strong>{{$trip->depart_time}}</strong></span>
                    </div>
                    <div>
                        <span>Arrival time: </span><span><strong>{{$trip->arrival_time}}</strong></span>
                    </div>
                    <div>
                        <span>Bus info: </span><span><strong>{{$trip->bus->merchant->name}}</strong>  <strong> {{' ('.$trip->bus->reg_number.') '}} </strong></span>
                    </div>
                    <div>
                    <div>
                        <span>Seat name: </span><span><strong>{{$trip->bus->seat_name}}</strong></span>
                    </div>
                    <div>
                        <span>Ticket price: </span><span><strong>{{$trip->price}}</strong>  <strong> {{' (Tshs) '}} </strong></span>
                    </div>
                </div>--}}
                </div>
            </div>
        </div>

    </section>

@endsection

@section('import_css')
    <link rel="stylesheet" href="{{asset('css/user_booking.css')}}">
@endsection

@section('import_js')

@endsection