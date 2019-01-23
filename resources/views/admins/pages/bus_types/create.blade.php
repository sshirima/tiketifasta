@extends('admins.layouts.master')

@section('import_css')
    <link rel="stylesheet" href="{{ URL::asset('css/seat_charts/jquery.seat-charts.css') }}">
@endsection

@section('title')
    {{ __('admin_pages.page_bustype_create_title') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            {{__('admin_page_bus_types.content_header_title_create')}}
            <small>{{__('admin_page_bus_types.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.bustype.index')}}"> {{__('admin_page_bus_types.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_bus_types.navigation_link_create')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.buses.buses_panel')
            </div>
            <div class="tab-content">
                <div class="row">
                    <div class="container col-md-12">
                        <form class="form-horizontal" role="form" method="post" action="{{route('admin.bustype.store')}}" accept-charset="UTF-8" style="padding: 20px">
                            @include('admins.pages.bus_types.fields')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('import_js')
    <script src="{{URL::asset('js/seat_charts/jquery.seat-charts.js')}}"></script>
    <script src="{{ URL::asset('js/admin/bustype_create.js') }}"></script>
@endsection