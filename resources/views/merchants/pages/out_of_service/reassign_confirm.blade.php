@extends('merchants.layouts.master')

@section('title')
    {{ __('merchant_pages.page_oos_reassign_confirm_title') }}
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
    @include('includes.errors.message')
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h4> Canceled bus</h4>
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
        <div class="col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h4> Re-assigned bus</h4>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td>Reg number:</td>
                            <td>{{$reassignedBus->reg_number}}</td>
                        </tr>
                        <tr>
                            <td>Merchant name:</td>
                            <td>{{$reassignedBus->merchant->name}}</td>
                        </tr>
                        <tr>
                            <td>Available of seats:</td>
                            <td>{{$reassignedBus->busType->seats}}</td>
                        </tr>
                        <tr>
                            <td>Class:</td>
                            <td>{{$reassignedBus->class}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if(session()->has('successMessage'))
        <div class="alert alert-success">{{ session()->get('successMessage') }}</div>
    @else
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4> {{__('merchant_pages.page_oos_reassign_confirm_panel_title')}}</h4>
            </div>
            <div class="panel-body">
                <form method="POST" class="form-horizontal"
                      action="{{route('merchant.buses.oos.reassign.confirm',[$dailyTimetable->busRoute->bus->id,$dailyTimetable->id])}}"
                      accept-charset="UTF-8">
                    <input name="_token" type="hidden" value="{{csrf_token()}}">
                    <input name="reassigned_bus_id" type="hidden" value="{{$reassignedBus->id}}">
                    <input name="old_schedule_id" type="hidden" value="{{$dailyTimetable->id}}">
                    <div class="form-group">
                        <div class="col-sm-12">
                            You are about to re-assign all of the booking on this bus to another bus<br>
                            Please note that this operation remove all the booking of this bus and assign them to another bus<br>
                            Note: This operation is subject to <strong>cost</strong>, as per the agreed term and conditions<br>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reassign_comment" class="col-sm-2">Comments:</label>
                        <div class="col-sm-6">
                        <textarea name="reassign_comment" id="reassign_comment" class="form-control"
                                  placeholder="Re-assignment comments"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <button class="btn btn-primary" type="submit"><i class="far fa-check-circle"></i> Confirm re-assignment</button>
                        </div>
                        <div class="col-sm-1">
                            <a href="{{route('merchant.buses.oos.reassign',[$dailyTimetable->busRoute->bus->id,$dailyTimetable->id])}}"><span
                                        class="btn btn-danger"> <i class="fas fa-arrow-circle-left"></i> Back</span></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

@endsection