@extends('admins.layouts.master')

@section('custom-import')
    <script src="{{ URL::asset('js/admin/bus_create.js') }}"></script>
@endsection

@section('title')
    {{ __('admin_pages.page_bus_create_form_title') }}
@endsection

@section('panel_heading')
    @include('admins.pages.buses.buses_panel')
@endsection

@section('panel_body')
    @component('includes.components.info-box',['info'=> __('admin_pages_info.buses_create_info')])@endcomponent
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>{{__('admin_pages.page_bus_create_form_title')}}</h5>
        </div>
        <div class="panel-body">
            @include('includes.errors.message')
            <div class="col-md-8">
                <form class="form-horizontal" role="form" method="post"
                      action="{{route(\App\Http\Controllers\Admins\BusController::ROUTE_STORE)}}" accept-charset="UTF-8" style="padding: 20px">
                    @include('admins.pages.buses.fields')
                </form>
            </div>
        </div>
    </div>
@endsection