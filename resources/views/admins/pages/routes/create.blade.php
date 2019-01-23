@extends('admins.layouts.master')

@section('custom-import')
    <link rel="stylesheet" href="{{ URL::asset('css/admin/routes_create.css') }}">
@endsection

@section('title')
    {{ __('admin_page_routes.page_title') }}
@endsection


@section('content-head')
    <section class="content-header">
        <h1>
            {{__('admin_page_routes.content_header_title')}}
            <small>{{__('admin_page_routes.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.routes.index')}}"> {{__('admin_page_routes.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_routes.navigation_link_create')}}</li>
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
                    <div class="col-md-6">
                        <form class="form-horizontal" role="form" method="post"
                              action="{{route('admin.routes.store')}}" accept-charset="UTF-8" style="padding: 20px">
                            @include('admins.pages.routes.fields')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('import_css')
    <!-- Select 2 from CDN -->
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/select2/dist/css/select2.min.css')}}">
@endsection
@section('import_js')
    <script src="{{ URL::asset('adminlte/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('js/admin/routes_create.js') }}"></script>

    <script type="text/javascript">
        //Select2

    </script>
@endsection