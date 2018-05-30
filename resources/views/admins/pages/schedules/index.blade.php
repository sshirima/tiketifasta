@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_schedules_index_title') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            {{__('admin_page_schedules.content_header_title')}}
            <small>{{__('admin_page_schedules.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.schedules.index')}}"> {{__('admin_page_schedules.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_schedules.navigation_link_create')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-header">
                <form class="navbar-form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input class="form-control" placeholder="Search" name="date" id="date" type="date" value="{{old('date')}}">
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