@extends('merchants.layouts.master')

@section('title')
    {{ __('merchant_page_buses.page_title_bus_schedules') }}
@endsection


@section('content-head')
    <section class="content-header">
        <h1>
            {{__('merchant_page_buses.content_header_title_bus_schedules')}}
            <small>{{__('merchant_page_buses.content_header_sub_title_bus_schedules')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('merchant.buses.index')}}"> {{__('merchant_page_buses.navigation_link_index')}}</a>
            </li>
            @if(isset($bus))
                <li class="active">{{__('merchant_page_buses.navigation_link_bus_schedules')}}</li>
            @else
                <li class="active">{{__('merchant_page_buses.navigation_link_bus_schedules')}}</li>
            @endif

        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('merchants.pages.buses.buses_edit_panel')
            </div>
            <div class="tab-content">
                @if(isset($bus->route))
                    <div class="form-horizontal" style="padding: 20px">
                        <div class="box box-success">
                            <div class="box-header">
                                <h5> {{__('merchant_page_buses.box_header_title_show_route')}}</h5>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    {!! Form::label('route_id', __('merchant_page_buses.form_field_label_route_name'), ['class'=>'col-sm-3 control-label', 'for'=>'route_id']) !!}
                                    <div class="col-sm-5">
                                        <input class="form-control" value="{{$bus->route->route_name}}" disabled>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <form class="form-horizontal" role="form" method="post"
                              action="{{route('merchant.buses.schedules.store',$bus->id)}}"
                              accept-charset="UTF-8" style="padding: 20px">
                            <div class="box box-success">
                                <div class="box-header">
                                    <h5> Bus schedules</h5>
                                </div>
                                <div class="box-body">
                                    {{--<div class="datepicker"></div>--}}
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>From</th>
                                                    <th>To</th>
                                                    <th>Depart</th>
                                                    <th>Arrival</th>
                                                    <th>Travelling days</th>
                                                    <th>Price</th>
                                                    <th>
                                                        <input id="direction" name="direction" type="checkbox" data-toggle="toggle"
                                                               checked data-on="Going" data-off="Return"
                                                               data-size="mini" data-onstyle="success"
                                                               data-offstyle="info">
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody id="trips">
                                                @foreach($bus->trips as $key=>$trip)
                                                    @if($trip->direction == 'GO')
                                                        <tr class="route-trips">
                                                            <td>{{$trip->from->name}}</td>
                                                            <td>{{$trip->to->name}}</td>
                                                            <td>{{$trip->depart_time}}</td>
                                                            <td>{{$trip->arrival_time}}</td>
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
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div id="calendar"></div>
                                </div>
                            </div>
                            <div class="box box-success">
                                <div class="box-header">
                                    <h5> Add travelling days</h5>
                                </div>
                                <div class="box-body">

                                    <div class="form-group">
                                        {!! Form::label('trip_dates', __('merchant_page_buses.form_field_label_select_dates'), ['class'=>'col-sm-2 control-label', 'for'=>'trip_dates']) !!}
                                        <div class="col-sm-2">
                                            {{Form::select('direction',[0=>'Direction','GO'=>'GO','RETURN'=>'RETURN'],null,['class'=>'form-control select2'])}}
                                        </div>
                                        <div class="col-sm-8">
                                            {{Form::select('trip_dates[]',$dates,null,['class'=>'form-control select2','multiple'=>'multiple'])}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-5 col-md-offset-5">
                                            {!! Form::submit(__('merchant_page_buses.form_field_button_save_schedule'), ['class' => 'btn btn-success']) !!}
                                            <a href="{!! route('merchant.buses.index') !!}"
                                               class="btn btn-default">{{__('merchant_page_buses.form_field_button_cancel')}}</a>
                                        </div>
                                    </div>
                                    @csrf
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="form-horizontal">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <span><h4 class="label label-warning">No route has been assigned yet</h4></span>
                            </div>
                            <div class="col-sm-6">
                                <a class="btn btn-success pull-right"
                                   href="{{route('merchant.buses.assign_routes',$bus->id)}}">Assign route</a>
                            </div>

                        </div>
                    </div>
                @endif
            </div>
            @csrf
        </div>
    </section>
@endsection

@section('import_css')
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/fullcalendar/dist/fullcalendar.min.css')}}">

    <link rel="stylesheet" href="{{asset('adminlte/bower_components/fullcalendar/dist/fullcalendar.print.min.css')}}"
          media="print">
    <!-- Select 2 from CDN -->
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/select2/dist/css/select2.min.css')}}">

    <link rel="stylesheet"
          href="{{asset('adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('lib/toggle/bootstrap-toggle.min.css')}}">

@endsection

@section('import_js')
    <script src="{{ URL::asset('js/toaster/jquery.toaster.js') }}"></script>
    <script src="{{ URL::asset('adminlte/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('adminlte/bower_components/moment/moment.js') }}"></script>
    <script src="{{ URL::asset('adminlte/bower_components/fullcalendar/dist/fullcalendar.min.js') }}"></script>

    <script src="{{ URL::asset('adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('js/merchant/schedule_assign.js') }}"></script>
    <script src="{{ URL::asset('lib/toggle/bootstrap-toggle.min.js') }}"></script>

    <script type="text/javascript">
        var bus_trips = {!! json_encode($bus->trips) !!};
        var schedules = {!! json_encode($schedules) !!};
    </script>

    <script type="text/javascript">
        //Select2
        $('.select2').select2();
    </script>

@endsection