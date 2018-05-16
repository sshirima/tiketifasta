@extends('admins.layouts.master')
@section('custom-import')
    <link rel="stylesheet" href="{{ URL::asset('css/admin/routes_create.css') }}">
    <script src="{{ URL::asset('js/admin/routes_create.js') }}"></script>
@endsection

@section('title')
    {{ __('admin_pages.page_routes_create_title') }}
@endsection

@section('panel_heading')
    @include('admins.pages.routes.route_panel')
@endsection

@section('panel_body')
    @component('includes.components.info-box',['info'=> __('admin_pages_info.routes_create_info')])@endcomponent
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>{{__('admin_pages.page_routes_create_form_title')}}</h5>
        </div>
        <div class="panel-body">
            @include('includes.errors.message')
            <div class="col-md-6">
                <form class="form-horizontal" role="form" method="post"
                      action="{{route('admin.route.store')}}" accept-charset="UTF-8" style="padding: 20px">
                    @include('admins.pages.routes.fields')
                </form>
            </div>
        </div>
    </div>
@endsection