@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_approve_timetable_show_title') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            <h3>{{__('admin_pages.page_approve_bus_route_confirm_form_title')}} </h3>
            <small>{{__('admin_page_approvals.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.approvals.index')}}"> {{__('admin_page_approvals.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_approvals.navigation_link_show_timetables')}}</li>
        </ol>
    </section>

@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-header">
                <h4>{{__('admin_pages.page_approve_timetable_show_form_title',
        ['route_name'=>$busRoute->route->route_name,'bus_name'=>$busRoute->bus->reg_number])}} </h4>

            </div>
            <div class="box-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form class="navbar-form">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Search" name="date" id="date" type="date"
                                               value="{{old('date')}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {{-- <div class="form-group">
                                         {{Form::select('route_id',$routes,old('route_id'),['class'=>'form-control'])}}
                                     </div>--}}
                                </div>
                                <div class="col-md-3">
                                    {{--<div class="form-group">
                                        {{Form::select('merchant_id',$merchants,old('merchant_id'),['class'=>'form-control'])}}
                                    </div>--}}
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-primary pull-right" type="submit"><i
                                                class="glyphicon glyphicon-refresh"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br>
                    <div class="panel-body">

                        <form class="form-horizontal" method="GET" action="{{route('admin.bus-route.approve.confirm',$busRoute->id)}}"
                              accept-charset="UTF-8">
                            <input name="_token" type="hidden" value="{{csrf_token()}}">
                            <div class="form-group">
                                {!! $table->render() !!}
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3">
                                    <button class="btn btn-primary" type="submit"> Authorize route</button>
                                    <a href="{{route('admin.bus-route.inactive.show')}}"><span
                                                class="btn btn-danger"><i class="fas fa-ban"></i> Cancel authorization</span></a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection