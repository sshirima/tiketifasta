@extends('admins.layouts.master')

@section('title')
    Prices
@endsection


@section('content-head')
    <section class="content-header">
        <h1>
            {{__('merchant_page_buses.content_header_title_bus_schedules')}}
            <small>{{__('merchant_page_buses.content_header_sub_title_bus_schedules')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.buses.index')}}"> {{__('merchant_page_buses.navigation_link_index')}}</a>
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
                @include('admins.pages.buses.buses_edit_panel')
            </div>
            <div class="tab-content">
                @if(isset($bus->route))
                    <div class="form-horizontal" style="padding: 20px">
                        <div class="box box-success">
                            <div class="box-header">
                                <h5> {{count($bus->trips)>0?__('merchant_page_buses.box_header_title_show_route'):__('merchant_page_buses.box_header_title_assign_route')}}</h5>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    {!! Form::label('route_id', __('merchant_page_buses.form_field_label_route_name'), ['class'=>'col-sm-3 control-label', 'for'=>'route_id']) !!}
                                    <div class="col-sm-5" >
                                        <input class="form-control" value="{{$bus->route->route_name}}" disabled>
                                    </div>

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
                                                    <td>{{$trip->depart_time}}</td>
                                                    <td>{{$trip->arrival_time}}</td>
                                                    <td>{{$trip->travelling_days}}</td>
                                                    <td>
                                                        <div id="{{'prices-'.$trip->id}}">
                                                            @if($trip->price == null)
                                                                <div class="label label-warning {{'trip-prices-'.$trip->id}}">Not set</div>
                                                            @else
                                                                <input class="form-control {{'trip-prices-'.$trip->id}}" id="price-value-{{$trip->id}}" value="{{$trip->price}}" disabled>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    {{--<td>
                                                        @if($trip->price == null)
                                                            <button class="btn btn-xs btn-primary" name="{{'set-price-'.$trip->id}}" value="{{$trip->id}}">Set price </button>
                                                        @else
                                                            <button class="btn btn-xs btn-primary" name="{{'update-price-'.$trip->id}}" value="{{$trip->id}}">Update </button>
                                                        @endif
                                                    </td>--}}
                                                </tr>
                                                @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <span ><h4 class="label label-warning">No trips found</h4></span>
                                        </div>
                                        <div class="col-sm-6">
                                            <a class="btn btn-success pull-right" href="{{route('merchant.buses.assign_routes',$bus->id)}}">Create trips</a>
                                        </div>

                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @else
                   <div class="form-horizontal">
                       <div class="form-group">
                           <div class="col-sm-6">
                               <span ><h4 class="label label-warning">No route has been assigned yet</h4></span>
                           </div>
                           <div class="col-sm-6">
                               <a class="btn btn-success pull-right" href="{{route('merchant.buses.assign_routes',$bus->id)}}">Assign route</a>
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

@endsection

@section('import_js')

    <script src="{{ URL::asset('js/toaster/jquery.toaster.js') }}"></script>
    <script src="{{ URL::asset('js/merchant/trip_price_assign.js') }}"></script>

@endsection