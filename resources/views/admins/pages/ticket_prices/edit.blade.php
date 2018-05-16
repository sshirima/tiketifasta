@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_ticket_prices_update_title') }}
@endsection

@section('panel_heading')
    @include('admins.pages.routes.route_panel')
@endsection

@section('panel_body')
    @component('includes.components.info-box',['info'=> __('admin_pages_info.ticket_prices_create_info')])@endcomponent
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>{{__('admin_pages.page_ticket_prices_update_form_title')}}</h5>
        </div>
        <div class="panel-body">
            @include('includes.errors.message')
            <div class="col-md-6">
                <form class="form-horizontal" role="form" method="post"
                      action="{{route('admin.ticket_prices.update',[$ticketPrice->id])}}" accept-charset="UTF-8" style="padding: 20px">
                    @include('admins.pages.ticket_prices.fields')
                </form>
            </div>
        </div>
    </div>
@endsection