@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_locations_title_index') }}
@endsection

@section('panel_heading')
    @include('admins.pages.routes.route_panel')
@endsection

@section('panel_body')
    @include('flash::message')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 >{{__('admin_pages.page_locations_panel_title_locations')}}</h4>
        </div>
        <div class="panel-body">
            @include('includes.errors.message')
            {!! $table->render() !!}
        </div>
    </div>
@endsection