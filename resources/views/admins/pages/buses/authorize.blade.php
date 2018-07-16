@extends('admins.layouts.master')

@section('title')
    {{ __('merchant_page_buses.page_title_authorize') }}
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
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.buses.buses_edit_panel')
            </div>
            <div class="tab-content">
                <div class="form-horizontal" style="padding: 20px">
                    @if($bus->merchant->status && isset($bus->route) &&
                    isset($bus->merchant->contract_start) && isset($bus->merchant->contract_end) && (new DateTime($bus->merchant->contract_end) > new DateTime('now')))
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Merchant status</label>
                            <div class="col-sm-3">
                                <h5><span class="label label-success">Active</span></h5>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Bus condition</label>
                            <div class="col-sm-8">
                                @if($bus->condition == 'OPERATIONAL')
                                    <h5><span class="label label-success"> Operational</span></h5>
                                @elseif($bus->condition == 'MAINTANANCE')
                                    <h5><span class="label label-warning"> Maintanance</span></h5> <h5> Please confirm and change the condition of the bus for it to be authorized</h5>
                                @elseif($bus->condition == 'ACCIDENT')
                                    <h5><span class="label label-danger"> Accident</span></h5>
                                    @else
                                    <h5><span class="label label-default"> Not set</span></h5>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Bus route</label>
                            <div class="col-sm-3">
                                <h5><span class="label label-success"> Route is set</span></h5>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Trips</label>
                            <div class="col-sm-3">
                                @if(count($bus->trips) > 0)
                                    <h5><span class="label label-success"> Trips has been set</span></h5>
                                @else
                                    <h5><span class="label label-warning"> No trip assigned</span></h5>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Trips prices</label>
                            <div class="col-sm-3">
                                @if($bus->trip_price_status)
                                    <h5><span class="label label-success"> Prices has been set</span></h5>
                                @else
                                    <h5><span class="label label-warning"> Trips prices not set</span></h5>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Bus status</label>
                            <div class="col-sm-3">
                                @if($bus->state == 'DISABLED')
                                    <h5><span class="label label-danger"> Disabled</span></h5>
                                @elseif($bus->state == 'ENABLED')
                                    <h5><span class="label label-success"> Enabled</span></h5>
                                @elseif($bus->state == 'Suspended')
                                    <h5><span class="label label-warning"> Suspended</span></h5>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"></label>
                            <div class="col-sm-3">
                                @if($bus->state == 'DISABLED' && $bus->condition == 'OPERATIONAL')
                                    {!! Form::open(['route' => ['admin.buses.enable', $bus->id], 'method' => 'post','class'=>'form-horizontal']) !!}
                                    <button class="btn btn-primary" type="submit"> Enable bus</button>
                                    {!! Form::close() !!}
                                @endif
                                @if($bus->state == 'ENABLED')
                                    {!! Form::open(['route' => ['admin.buses.disable', $bus->id], 'method' => 'post','class'=>'form-horizontal']) !!}
                                    <button class="btn btn-danger" type="submit"> Disable bus</button>
                                    {!! Form::close() !!}
                                @endif
                            </div>
                        </div>
                    @else
                        <form class="form-horizontal" role="form" method="get"
                              action="{{isset($bus)?route('admin.merchant.authorize', $bus->merchant->id):route('admin.buses.store')}}"
                              accept-charset="UTF-8" style="padding: 20px">
                            @csrf
                            <div class="form-group">
                                <div class="col-sm-12 text-center">
                                    Merchant account is not enabled, please enable merchant account first before
                                    authorizing the bus
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-7 col-md-offset-5">
                                    {!! Form::submit('Authorize merchant', ['class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </section>
@endsection

@section('import_css')
    <link rel="stylesheet" href="{{ URL::asset('css/seat_charts/jquery.seat-charts.css') }}">
@endsection

@section('import_js')
    <script src="{{URL::asset('js/seat_charts/jquery.seat-charts.js')}}"></script>
    <script src="{{ URL::asset('js/merchant/show_bus_seats.js') }}"></script>
    <script type="text/javascript">
        var seats = {!! json_encode($bus->seats) !!};
        var seat_arrangement = {!! json_encode($bus->busType->seat_arrangement) !!};
    </script>
@endsection