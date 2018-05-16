@extends('merchants.layouts.master')

@section('title')
    {{ __('merchant_pages.page_oos_reassign_title') }}
@endsection

@section('panel_heading')
    @include('merchants.pages.buses.buses_edit_panel')
@endsection

@section('panel_body')
    {{--@include('flash::message')
    @component('includes.components.info-box',['info'=> 'Here can stay some info'])@endcomponent
    <section class="content-header">
        <h3>{{__('merchant_pages.page_oos_reassign_form_title')}} </h3>
    </section>
    <br>--}}
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h4> {{__('merchant_pages.page_oos_reassign_panel_title_cancelled_bus')}}</h4>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td>Reg number:</td>
                            <td>{{$dailyTimetable->busRoute->bus->reg_number}}</td>
                        </tr>
                        <tr>
                            <td>Number of seats:</td>
                            <td>{{$dailyTimetable->busRoute->bus->busType->seats}}</td>
                        </tr>
                        <tr>
                            <td>Bookings:</td>
                            <td>{{count($dailyTimetable->bookings)}}</td>
                        </tr>
                        <tr>
                            <td>Class:</td>
                            <td>{{$dailyTimetable->busRoute->bus->class}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4> {{__('merchant_pages.page_oos_reassign_panel_title_my_company')}}</h4>
        </div>
        <div class="panel-body">
            {!! $table->render() !!}
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4> {{__('merchant_pages.page_oos_reassign_panel_title_another_company')}}</h4>
        </div>
        <div class="panel-body">

        </div>
    </div>
@endsection