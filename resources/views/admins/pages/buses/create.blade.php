@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_bus_create_form_title') }}
@endsection


@section('content-head')
    <section class="content-header">
        <h1>
            {{__('admin_page_buses.content_header_title')}}
            <small>{{__('admin_page_buses.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.buses.index')}}"> {{__('admin_page_buses.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_buses.navigation_link_create')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box box-default">
            <div class="box-header">
                <h5>{{__('admin_pages.page_bus_create_form_title')}}</h5>
            </div>
            <div class="box-body">
                <div class="col-md-8">
                    <form class="form-horizontal" role="form" method="post"
                          action="{{route(\App\Http\Controllers\Admins\BusController::ROUTE_STORE)}}" accept-charset="UTF-8" style="padding: 20px">
                        @include('admins.pages.buses.fields')
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('import_js')
    <script src="{{ URL::asset('js/admin/bus_create.js') }}"></script>
@endsection