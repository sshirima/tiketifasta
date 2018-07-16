@extends('merchants.layouts.master')

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
                <a href="{{route('merchant.buses.index')}}"> {{__('merchant_page_buses.navigation_link_index')}}</a>
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
                @include('merchants.pages.buses.buses_edit_panel')
            </div>
            <div class="tab-content">
                @if(count($bus->trips)>0)
                @else
                    <form class="form-horizontal" role="form" method="post"
                          action="{{route('merchant.buses.assign_routes',$bus->id)}}" accept-charset="UTF-8"
                          style="padding: 20px">
                        @endif
                        <div class="box box-success">
                            <div class="box-header">
                                <h5> {{count($bus->trips)>0?__('merchant_page_buses.box_header_title_show_route'):__('merchant_page_buses.box_header_title_assign_route')}}</h5>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    @if(count($bus->trips)>0)
                                        {!! Form::label('route_id', __('merchant_page_buses.form_field_label_route_name'), ['class'=>'col-sm-3 control-label', 'for'=>'route_id']) !!}
                                    @else
                                        {!! Form::label('route_id', __('merchant_page_buses.form_field_label_select_route'), ['class'=>'col-sm-3 control-label', 'for'=>'route_id']) !!}
                                    @endif

                                    <div class="col-sm-5">
                                        {{Form::select('route_id',$routes,$bus->tripCount>0?$bus->route_id:null,['class'=>'form-control select2',$bus->tripCount>0?'disabled':''])}}
                                    </div>
                                    @if(count($bus->trips)>0)
                                        <div class="col-sm-4">
                                            <a href="#">
                                                <div class="btn btn-warning">Remove route</div>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="box box-success">
                            <div class="box-header">
                                <h5> {{count($bus->trips)>0?__('merchant_page_buses.box_header_title_assign_trip'):__('merchant_page_buses.box_header_title_assign_trip')}}</h5>
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
                                            <th>Travelling days</th>
                                            <th>Price</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($bus->trips as $key=>$trip)
                                            <tr>
                                                <td>{{$trip->from->name}}</td>
                                                <td>{{$trip->to->name}}</td>
                                                <td>
                                                    <div class="bootstrap-timepicker" >
                                                        <input type="text"
                                                               class="form-control {{'trip-time-'.$trip->id}} timepicker "
                                                               name="depart_time_{{$trip->id}}"
                                                               id="depart_time_{{$trip->id}}"
                                                               value="{{$trip->depart_time}} " disabled>
                                                    </div>

                                                </td>
                                                <td>
                                                    <div class="bootstrap-timepicker" >
                                                    <input type="text"
                                                           class="form-control {{'trip-time-'.$trip->id}} timepicker"
                                                           name="arrival_time_{{$trip->id}}"
                                                           id="arrival_time_{{$trip->id}}"
                                                           value="{{$trip->arrival_time}}" disabled>
                                                    </div>
                                                </td>
                                                <td>{{$trip->travelling_days}}</td>
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
                                                <td>
                                                    <button class="btn btn-xs btn-primary" id="{{'update-time-'.$trip->id}}"
                                                            name="{{'update-time-'.$trip->id}}" value="{{$trip->id}}">
                                                        Update
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div id="trips">
                                        <div class="route-trips"></div>
                                    </div>
                                @endif
                                {{--@if($bus->tripCount>0)
                                    {!! $tripTable->render() !!}
                                @else

                                @endif--}}

                            </div>
                        </div>

                        {{--<div class="box box-success">
                            <div class="box-header">
                                <h5> Travelling days</h5>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    {!! Form::label('trip_dates', __('merchant_page_buses.form_field_label_select_dates'), ['class'=>'col-sm-2 control-label', 'for'=>'trip_dates']) !!}
                                    <div class="col-sm-10" >
                                        {{Form::select('trip_dates[]',$dates,null,['class'=>'form-control select2','multiple'=>'multiple'])}}
                                    </div>
                                </div>
                            </div>
                        </div>--}}

                        @csrf
                        @if($bus->tripCount>0)
                        @else
                            <div class="form-group">
                                <div class="col-sm-5 col-md-offset-5">
                                    {!! Form::submit(__('merchant_page_buses.form_field_button_assign_route'), ['class' => 'btn btn-success']) !!}
                                    <a href="{!! route('merchant.buses.index') !!}"
                                       class="btn btn-default">{{__('merchant_page_buses.form_field_button_cancel')}}</a>
                                </div>
                            </div>
                        @endif
                        @if(count($bus->trips)>0)
                        @else
                    </form>
                @endif

            </div>
        </div>
    </section>
@endsection

@section('import_css')
    <!-- Select 2 from CDN -->
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/select2/dist/css/select2.min.css')}}">

@endsection

@section('import_js')
    <script src="{{URL::asset('adminlte/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{URL::asset('adminlte/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <!-- JQuery custom code -->
    <script type="text/javascript">
        //Select2
        $('.select2').select2();

        $('#trip_dates').select2();
    </script>
    <script src="{{ URL::asset('js/toaster/jquery.toaster.js') }}"></script>
    <script src="{{ URL::asset('js/merchant/bus_route_assign.js') }}"></script>

@endsection