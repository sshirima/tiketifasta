@extends('admins.layouts.master')

@section('title')
    @lang('Create new station')
@endsection


@section('content-head')
    {{--<section class="content-header">
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
    </section>--}}
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                <li class="{{Request::is('admin/stations/create') ? 'active' : ''}}"><a href="{{route('admin.stations.index')}}" >@lang('Create new station')</a></li>

            </div>
            <div class="tab-content">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data"
                      action="{{route('admin.stations.store')}}"
                      accept-charset="UTF-8" style="padding: 20px">
                    <div class="form-group">
                        {!! Form::label('st_name', __('Station name'), ['class'=>'col-sm-3 control-label', 'for'=>'st_name']) !!}
                        <div class="col-sm-5">
                            {!! Form::text('st_name', old('st_name'), ['class' => 'form-control', 'placeholder'=>__('Enter station name')]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('st_type', __('Station type'), ['class'=>'col-sm-3 control-label', 'for'=>'st_type']) !!}
                        <div class="col-sm-5">
                            {{Form::select('st_type',$station_types,null,['class'=>'form-control select2'/*,isset($bus)?'disabled':''*/])}}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('location_id', __('Location'), ['class'=>'col-sm-3 control-label', 'for'=>'location_id']) !!}
                        <div class="col-sm-5">
                            {{Form::select('location_id',$locations,null,['class'=>'form-control select2'/*,isset($bus)?'disabled':''*/])}}
                        </div>
                    </div>
                    @csrf
                    <div class="form-group">
                        <div class="col-sm-7 col-md-offset-2">
                            {!! Form::submit(__('Submit'), ['class' => 'btn btn-primary']) !!}
                            <a href="{!! route('admin.stations.index') !!}"
                               class="btn btn-default">{{__('Cancel')}}</a>
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