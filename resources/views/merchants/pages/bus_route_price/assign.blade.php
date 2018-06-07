@extends('merchants.layouts.master')

@section('title')
    {{ __('merchant_page_buses.page_title_bus_route_price') }}
@endsection


@section('content-head')
    <section class="content-header">
        <h1>
            {{__('merchant_page_buses.content_header_title_bus_route_price')}}
            <small>{{__('merchant_page_buses.content_header_sub_title_bus_route_price')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('merchant.buses.index')}}"> {{__('merchant_page_buses.navigation_link_index')}}</a>
            </li>
            @if(isset($bus))
                <li class="active">{{__('merchant_page_buses.navigation_link_bus_price')}}</li>
            @else
                <li class="active">{{__('merchant_page_buses.navigation_link_bus_price')}}</li>
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
                <form class="form-horizontal" role="form" method="post" action="{{route('merchant.buses.assign_routes',$bus->id)}}" accept-charset="UTF-8" style="padding: 20px">
                    <div class="box box-success">
                        <div class="box-header">
                            <h5> {{$bus->tripCount>0?__('merchant_page_buses.box_header_title_show_route'):__('merchant_page_buses.box_header_title_assign_route')}}</h5>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                @if(isset($tripTable))
                                    {!! Form::label('route_id', __('merchant_page_buses.form_field_label_route_name'), ['class'=>'col-sm-3 control-label', 'for'=>'route_id']) !!}
                                @else
                                    {!! Form::label('route_id', __('merchant_page_buses.form_field_label_select_route'), ['class'=>'col-sm-3 control-label', 'for'=>'route_id']) !!}
                                @endif

                                <div class="col-sm-5" >
                                    <input class="form-control" value="Route name" disabled>
                                    
                                </div>
                                    @if(isset($tripTable))
                                        <div class="col-sm-4" >
                                            <a href="#"><div class="btn btn-warning">Remove route</div></a>
                                        </div>
                                    @endif
                            </div>
                        </div>
                    </div>
                    <div class="box box-success">
                        <div class="box-header">
                            <h5> {{$bus->tripCount>0?__('merchant_page_buses.box_header_title_assign_trip'):__('merchant_page_buses.box_header_title_assign_trip')}}</h5>
                        </div>
                        <div class="box-body">
                            @if($bus->tripCount>0)
                                {!! $tripTable->render() !!}
                            @else
                                <div id="trips">
                                    <div class="route-trips"></div>
                                </div>
                            @endif

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
                                <a href="{!! route('merchant.buses.index') !!}" class="btn btn-default">{{__('merchant_page_buses.form_field_button_cancel')}}</a>
                            </div>
                        </div>
                    @endif

                </form>
            </div>
        </div>
    </section>
@endsection

@section('import_css')
    <!-- Select 2 from CDN -->
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/select2/dist/css/select2.min.css')}}">

@endsection

@section('import_js')
    <script src="{{ URL::asset('adminlte/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
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