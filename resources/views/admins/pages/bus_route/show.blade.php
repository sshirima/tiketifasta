@extends('admins.layouts.master')

@section('title')
    {{ __('merchant_page_buses.page_title_bus_route_assign') }}
@endsection


@section('content-head')
    <section class="content-header">
        <h1>
            {{__('merchant_page_buses.content_header_title_show')}}
            <small>{{__('merchant_page_buses.content_header_sub_title_show')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.buses.index')}}"> {{__('merchant_page_buses.navigation_link_index')}}</a>
            </li>
            @if(isset($bus))
                <li class="active">{{__('merchant_page_buses.navigation_link_show')}}</li>
            @else
                <li class="active">{{__('merchant_page_buses.navigation_link_show')}}</li>
            @endif

        </ol>
    </section>
@endsection

@section('content-body')
    <link rel="stylesheet" href="{{asset('adminlte/plugins/timepicker/bootstrap-timepicker.min.css')}}">
    
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.buses.buses_edit_panel')
            </div>
            <div class="tab-content">
                @if(isset($bus->route))
                    <div class="box box-success">
                        <div class="box-header">
                            <h5> {{__('merchant_page_buses.box_header_title_assign_route')}}</h5>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                {!! Form::label('route_id', __('merchant_page_buses.form_field_label_route_name'), ['class'=>'col-sm-3 control-label', 'for'=>'route_id']) !!}

                                <div class="col-sm-5">
                                    {{Form::select('route_id',$routes,$bus->route_id,['class'=>'form-control select2','disabled'])}}
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="box box-success">
                        <div class="box-header">
                            <h5> {{__('merchant_page_buses.box_header_title_show_bus_trip')}}</h5>
                        </div>
                        <div class="box-body">
                            @if(count($bus->trips)>0)
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Depart</th>
                                        <th>Arrival</th>
                                        <th>Price</th>
                                        <th>Travelling days</th>
                                        <th>Direction</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($bus->trips as $key=>$trip)
                                        <tr>
                                            <td>{{$trip->from->name}}</td>
                                            <td>{{$trip->to->name}}</td>
                                            <td>{{$trip->depart_time}}
                                                {{--<div class="bootstrap-timepicker" >
                                                    <input type="text"
                                                           class="form-control {{'trip-time-'.$trip->id}} timepicker "
                                                           name="depart_time_{{$trip->id}}"
                                                           id="depart_time_{{$trip->id}}"
                                                           value="{{$trip->depart_time}} " disabled>
                                                </div>--}}

                                            </td>
                                            <td>{{$trip->arrival_time}}
                                                {{--<div class="bootstrap-timepicker" >
                                                    <input type="text"
                                                           class="form-control {{'trip-time-'.$trip->id}} timepicker"
                                                           name="arrival_time_{{$trip->id}}"
                                                           id="arrival_time_{{$trip->id}}"
                                                           value="{{$trip->arrival_time}}" disabled>
                                                </div>--}}
                                            </td>
                                            <td>
                                                <div id="{{'prices-'.$trip->id}}">
                                                    @if($trip->price == null)
                                                        <div class="label label-warning {{'trip-prices-'.$trip->id}}">
                                                            Not set
                                                        </div>
                                                    @else
                                                        {{$trip->price}}
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{$trip->travelling_days}}</td>

                                            <td>
                                                @if($trip->direction == 'GO')
                                                    <div class="label label-success"><i
                                                                class="fas fa-arrow-circle-right"></i>
                                                        Going
                                                    </div>
                                                @else
                                                    <div class="label label-info"><i
                                                                class="fas fa-arrow-circle-left"></i>
                                                        Return
                                                    </div>
                                                @endif
                                            </td>

                                            {{--<td>
                                                <button class="btn btn-xs btn-primary" id="{{'update-time-'.$trip->id}}"
                                                        name="{{'update-time-'.$trip->id}}" value="{{$trip->id}}">
                                                    Update
                                                </button>
                                            </td>--}}
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <h3 class="label label-warning">{{__('merchant_page_buses.no_trip_assigned')}}</h3>
                            @endif
                            {{--@if(count($bus->trips)>0)
                                {!! $tripTable->render() !!}
                            @else

                            @endif--}}

                        </div>
                    </div>
                @else
                    <h3 class="label label-warning">{{__('merchant_page_buses.no_route_assigned')}}</h3>
                @endif

            </div>
        </div>
    </section>
@endsection

@section('import_css')

@endsection

@section('import_js')

@endsection