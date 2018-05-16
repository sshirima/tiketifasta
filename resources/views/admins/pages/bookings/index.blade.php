@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_bookings_index_title') }}
@endsection

@section('panel_heading')
    @include('admins.pages.bookings.booking_panel')
@endsection

@section('panel_body')
    @include('flash::message')
    @component('includes.components.info-box',['info'=> 'Here can stay some info'])@endcomponent

    <section class="content-header">
        <h3 >{{__('admin_pages.page_bookings_index_form_title')}} </h3>
    </section>
    <br>
    <div class="panel panel-default">
        <div class="panel-heading">
            <form class="navbar-form" >
                <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input class="form-control" name="date" id="date" type="date" value="{{old('date')}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{Form::select('merchant_id',$merchants,old('merchant_id'),['class'=>'form-control'])}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            {{Form::select('status',
                            [
                            0=>__('admin_pages.page_bookings_form_fields_select_booking_status'),
                            \App\Models\Booking::$STATUS_CONFIRMED=>__('admin_pages.page_bookings_form_fields_select_confirmed'),
                            \App\Models\Booking::$STATUS_PENDING=>__('admin_pages.page_bookings_form_fields_select_pending'),
                            \App\Models\Booking::$STATUS_CANCELLED=>__('admin_pages.page_bookings_form_fields_select_canceled')
                            ],
                            old('status'),['class'=>'form-control'])}}
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary pull-right" type="submit"><i class="glyphicon glyphicon-refresh"></i></button>
                        </div>
                    </div>
            </form>
        </div>
        <div class="panel-body">
            {!! $table->render() !!}
        </div>
    </div>
@endsection