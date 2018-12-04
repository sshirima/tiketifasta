@extends('users.layouts.master_v2')

@section('title')
    {{ __('user_pages.page_title_booking_details') }}
@endsection


@section('content')
    <section class="showcase">

        <div class="container-fluid p-0">

            <div class="row no-gutters">
                <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                    <div class="text-center">
                        @include('users.pages.bookings.booking_details_fields')
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                    <div>
                        @if(session('error'))
                            <div class="alert alert-danger">Error: {{session('error')}}</div>
                        @endif
                        <h3 class="mb-5">Trip information</h3>
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
                    </div>
                    <div>
                        <div>
                            <span>Seat name: </span><span><strong>{{$trip->bus->seat_name}}</strong></span>
                        </div>
                        <div>
                            <span>Ticket price: </span><span><strong>{{$trip->price}}</strong>  <strong> {{' (Tshs) '}} </strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection