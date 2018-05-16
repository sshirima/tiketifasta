@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_ticket_price_index_title') }}
@endsection

@section('panel_heading')
    @include('admins.pages.routes.route_panel')
@endsection

@section('panel_body')
    @include('flash::message')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 >{{__('admin_pages.page_ticket_price_index_form_tile')}}</h4>
        </div>
        <div class="panel-body">
            @include('includes.errors.message')
            {!! $table->render() !!}
        </div>
    </div>
@endsection