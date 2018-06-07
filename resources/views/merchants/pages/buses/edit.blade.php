@extends('merchants.layouts.master')

@section('title')
    {{ __('merchant_page_buses.page_title_edit') }}
@endsection


@section('content-head')
    <section class="content-header">
        <h1>
            {{__('merchant_page_buses.content_header_title')}}
            <small>{{__('merchant_page_buses.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('merchant.buses.index')}}"> {{__('merchant_page_buses.navigation_link_index')}}</a>
            </li>
            @if(isset($bus))
                <li class="active">{{__('merchant_page_buses.navigation_link_edit')}}</li>
            @else
                <li class="active">{{__('merchant_page_buses.navigation_link_edit')}}</li>
            @endif

        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('merchants.pages.buses.buses_edit_panel')
            </div>
            <div class="tab-content">
                <form class="form-horizontal" role="form" method="post"
                      action="{{isset($bus)?route('merchant.buses.update', $bus->id):route('merchant.buses.store')}}" accept-charset="UTF-8" style="padding: 20px">
                    @if(isset($bus))
                        <input type="hidden" name="_method" value="PUT">
                    @endif
                    <div class="form-group">
                        <div class="col-sm-7">
                            @include('merchants.pages.buses.fields_basic_info')
                        </div>
                        <div class="col-sm-5">
                            @include('merchants.pages.buses.fields_more_info')
                        </div>
                        @csrf
                        <div class="form-group">
                            <div class="col-sm-7 col-md-offset-5">
                                {!! Form::submit(isset($bus)?__('merchant_page_buses.form_field_button_edit'):__('merchant_page_buses.form_field_button_create'), ['class' => 'btn btn-primary']) !!}
                                <a href="{!! route('merchant.buses.index') !!}" class="btn btn-default">{{__('merchant_page_buses.form_field_button_cancel')}}</a>
                            </div>
                        </div>
                    </div>
                </form>
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

    <!-- JQuery custom code -->
    <script type="text/javascript">
        $('.select2').select2();
    </script>

@endsection