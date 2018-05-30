@extends('admins.layouts.master')

@section('title')
    {{ __('admin_page_bus_routes.page_title_index') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            {{__('admin_page_bus_routes.content_header_title')}}
            <small>{{__('admin_page_bus_routes.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.bus-routes.index')}}"> {{__('admin_page_bus_routes.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_bus_routes.navigation_link_view')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-header">
                <form class="navbar-form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{Form::select('status',$route_status,old('status'),['class'=>'form-control'])}}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{Form::select('route_id',$routes,old('route_id'),['class'=>'form-control'])}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{Form::select('merchant_id',$merchants,old('merchant_id'),['class'=>'form-control'])}}
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary pull-right" type="submit"><i class="glyphicon glyphicon-refresh"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="box-body">
                {!! $table->render() !!}
            </div>
        </div>
    </section>
@endsection
