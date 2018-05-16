@extends('merchants.layouts.master')

@section('custom-import')
    <link rel="stylesheet" href="{{ URL::asset('css/toggle-button.css') }}">
@endsection

@section('title')
    {{ __('merchant_pages.page_schedules_index_title') }}
@endsection

@section('panel_heading')
    @include('merchants.pages.buses.buses_edit_panel')
@endsection

@section('panel_body')
    @include('flash::message')
    @component('includes.components.info-box',['info'=> 'Here can stay some info'])@endcomponent
    <section class="content-header">
        <h3>{{__('merchant_pages.page_bus_schedules_index_form_title')}} </h3>
    </section>
    <br>
    <div class="panel panel-default">
        <div class="panel-heading">
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
                        {{--<div class="form-group">
                            {{Form::select('merchant_id',$merchants,old('merchant_id'),['class'=>'form-control'])}}
                        </div>--}}
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary pull-right" type="submit"><i class="glyphicon glyphicon-refresh"></i></button>
                    </div>
                </div>

            </form>
        </div>
        <div class="panel-body">
            {!! $table->render() !!}
        </div>
    </div>
@endsection