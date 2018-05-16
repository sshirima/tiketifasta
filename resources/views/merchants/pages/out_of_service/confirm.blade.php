@extends('merchants.layouts.master')

@section('title')
    {{ __('merchant_pages.page_oos_confirm_title') }}
@endsection

@section('panel_heading')
    @include('merchants.pages.buses.buses_edit_panel')
@endsection

@section('panel_body')
    @include('flash::message')
    @component('includes.components.info-box',['info'=> 'Here can stay some info'])@endcomponent
    {{--<section class="content-header">
        <h3>{{__('merchant_pages.page_oos_index_form_title')}} </h3>
    </section>
    <br>--}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4> Details</h4>
        </div>
        <div class="panel-body">
            @if(count($schedules->bookings) == 0)
                <form class="form-horizontal" role="form" method="POST"
                      action="{{route('merchant.buses.oos.change',$schedules->id)}}"
                      accept-charset="UTF-8">
                    <div class="form-group">
                        <label class="col-sm-3 control-label ">Bus number:</label>
                        <div class="col-sm-4">
                            <input disabled="true" class="form-control"
                                   value="{{$schedules->busRoute->bus->reg_number}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label ">Route name:</label>
                        <div class="col-sm-4">
                            <input disabled="true" class="form-control"
                                   value="{{$schedules->busRoute->route->route_name}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label ">Operation date:</label>
                        <div class="col-sm-4">
                            <input disabled="true" class="form-control" value="{{$schedules->day->date}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="details" class="control-label col-sm-3">Details of cancellation:</label>
                        <div class="col-sm-7">
                            <textarea name="details" id="details" class="form-control"
                                      value=""></textarea>
                        </div>
                    </div>
                    <input name="_token" type="hidden" value="{{csrf_token()}}">
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        @if($schedules->status)
                            <div class="col-sm-7">
                                <button class="btn btn-warning col-sm-4" type="submit"><i
                                            class="far fa-check-circle"></i>
                                    Deactivate
                                </button>
                                <a class="col-sm-4"
                                   href="{{route('merchant.buses.oos.index',$schedules->busRoute->bus->id)}}"><span
                                            class="btn btn-danger"> <i class="fas fa-arrow-circle-left"></i> Back</span></a>
                            </div>
                        @else
                            <div class="col-sm-7">
                                <button class="btn btn-primary col-sm-4" type="submit"><i
                                            class="far fa-check-circle"></i>
                                    Activate
                                </button>
                                <a class="col-sm-4"
                                   href="{{route('merchant.buses.oos.index',$schedules->busRoute->bus->id)}}"><span
                                            class="btn btn-danger"> <i class="fas fa-arrow-circle-left"></i> Back</span></a>
                            </div>
                        @endif
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h5> Trips for this days</h5>
                        </div>
                        <div class="panel-body">
                            @foreach($schedules->busRoute->subRoutes as $subRoute)
                                <div class="form-group">
                                    <div class="col-sm-1 control-label">From:</div>
                                    <div class="col-sm-2">
                                        <input disabled="true" class="form-control"
                                               value="{{$subRoute->source()->first()->name}}">
                                    </div>
                                    <div class="col-sm-1 control-label">To:</div>
                                    <div class="col-sm-2">
                                        <input disabled="true" class="form-control col-sm-6"
                                               value="{{$subRoute->destination()->first()->name}}">
                                    </div>
                                    <div class="col-sm-1 control-label">Depart:</div>
                                    <div class="col-sm-2">
                                        <input disabled="true" class="form-control"
                                               value="{{$subRoute->depart_time}}">
                                    </div>
                                    <div class="col-sm-1 control-label">Arrive:</div>
                                    <div class="col-sm-2">
                                        <input disabled="true" class="form-control col-sm-6"
                                               value="{{$subRoute->arrival_time}}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </form>

            @else
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="control-label col-sm-3">Scheduled bookings:</div>
                        <div class="col-sm-2">
                            <div class="form-control">
                                {{count($schedules->bookings)}}
                            </div>
                        </div>
                        <div class="col-sm-7"></div>
                    </div>
                    <div class="form-group">
                        <a class="btn btn-primary col-sm-3 col-sm-offset-3"
                           href="{{route('merchant.buses.oos.reassign',[$schedules->busRoute->bus->id, $schedules->id])}}"><span>
                                <i class="far fa-check-circle"></i>Re-assign to another bus</span></a>
                        <a class="col-sm-4"
                           href="{{route('merchant.buses.oos.index',$schedules->busRoute->bus->id)}}"><span
                                    class="btn btn-danger"> <i class="fas fa-ban"></i> Cancel bookings</span></a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection