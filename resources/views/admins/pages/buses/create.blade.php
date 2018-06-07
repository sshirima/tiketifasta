@extends('admins.layouts.master')

@section('title')
    @if(isset($bus))
        {{ __('admin_page_buses.page_title_edit') }}
    @else
        {{ __('admin_page_buses.page_title_create') }}
    @endif
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
            @if(isset($bus))
                <li class="active">{{__('admin_page_buses.navigation_link_edit')}}</li>
            @else
                <li class="active">{{__('admin_page_buses.navigation_link_create')}}</li>
            @endif

        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-header">
                @if(isset($bus))
                    <h4>{{__('admin_page_buses.form_title_edit')}}</h4>
                @else
                    <h4>{{__('admin_page_buses.form_title_create')}}</h4>
                @endif

            </div>
            <div class="box-body">
                <form class="form-horizontal" role="form" method="post"
                      action="{{isset($bus)?route('admin.buses.update', $bus->id):route('admin.buses.store')}}"
                      accept-charset="UTF-8" style="padding: 20px">
                    @if(isset($bus))
                        <input type="hidden" name="_method" value="PUT">
                    @endif
                    <div class="form-group">
                        <div class="col-sm-7">
                            @include('admins.pages.buses.fields_basic_info')
                        </div>
                        <div class="col-sm-5">
                            @include('admins.pages.buses.fields_more_info')
                        </div>
                    </div>

                    @csrf
                    <div class="form-group">
                        <div class="col-sm-7 col-md-offset-5">
                            {!! Form::submit(isset($bus)?__('merchant_page_buses.form_field_button_edit'):__('merchant_page_buses.form_field_button_create'), ['class' => 'btn btn-primary']) !!}
                            <a href="{!! route('merchant.buses.index') !!}"
                               class="btn btn-default">{{__('merchant_page_buses.form_field_button_cancel')}}</a>
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