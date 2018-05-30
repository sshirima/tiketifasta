@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_bookings_index_title') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            {{__('admin_page_bookings.content_header_title')}}
            <small>{{__('admin_page_bookings.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.bookings.index')}}"> {{__('admin_page_bookings.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_bookings.navigation_link_create')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-header">
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
            <div class="box-body">
                {!! $table->render() !!}
            </div>
        </div>
    </section>
@endsection