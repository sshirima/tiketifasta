@extends('merchants.layouts.master')

@section('title')
    {{ __('merchant_pages.page_bus_edit_title') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            {{__('merchant_page_buses.content_header_title_edit')}}
            <small>{{__('merchant_page_buses.content_header_sub_title_edit')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('merchant.buses.index')}}"> {{__('merchant_page_buses.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('merchant_page_buses.navigation_link_edit')}}</li>
        </ol>
    </section>
@endsection

@section('import_css')
    <link rel="stylesheet" href="{{ URL::asset('css/merchant/bus_edit.css') }}">
@endsection

@section('content-body')
    <section class="content container-fluid">
        <form class="form-horizontal" role="form" method="post" action="{{route('merchant.buses.update',$bus->id)}}" accept-charset="UTF-8" style="padding: 20px">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('merchants.pages.buses.buses_edit_panel')
            </div>
            <div class="tab-content">
                <div class="row">
                    <div class="col-md-6">
                        @include('merchants.pages.buses.fields_edit')
                    </div>
                    <div class="col-md-6">
                        {!! $table->render() !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-success">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="trip_fields"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-5 col-md-offset-5">
                {!! Form::submit(__('merchant_pages.page_bus_edit_field_button_update'), ['class' => 'btn btn-primary']) !!}
                <a href="{!! route('merchant.buses.index') !!}" class="btn btn-default">{{__('merchant_pages.page_bus_edit_field_button_cancel')}}</a>
            </div>
        </div>
        </form>
    </section>

@endsection

@section('import_js')
    <script type="text/javascript">
        var routes={!! json_encode($routes) !!}
    </script>
    <script src="{{ URL::asset('js/merchant/bus_create.js') }}"></script>
    <script src="{{ URL::asset('lib/datepicker/bootstrap-datepicker.js') }}"></script>
@endsection