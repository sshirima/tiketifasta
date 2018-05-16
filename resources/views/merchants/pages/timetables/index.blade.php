@extends('merchants.layouts.master')

@section('title')
    {{ __('merchant_pages.page_title_timetable_index') }}
@endsection

@section('panel_heading')
    @include('merchants.pages.timetables.timetable_panel')
@endsection

@section('panel_body')
    @include('flash::message')
    <section class="content-header">
        <h3 >{{__('merchant_pages.page_timetables_index_form_title')}} </h3>
    </section>
    <br>
    <div class="panel panel-default">
        <div class="panel-heading">
            <form class="navbar-form" >
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{Form::select('bus_id',$buses,old('bus_id'),['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{Form::select('route_id',$routes,old('route_id'),['class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="col-md-3">

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