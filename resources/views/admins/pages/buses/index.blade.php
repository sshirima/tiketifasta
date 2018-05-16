@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_bus_index_title') }}
@endsection

@section('panel_heading')
    @include('admins.pages.buses.buses_panel')
@endsection

@section('panel_body')
    @include('flash::message')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>{{__('admin_pages.page_bus_index_form_title')}}</h5>
        </div>
        <div class="panel-body">
            @include('includes.errors.message')
            {!! $table->render() !!}
        </div>
    </div>
@endsection