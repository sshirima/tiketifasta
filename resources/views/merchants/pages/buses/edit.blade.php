@extends('merchants.layouts.master')

@section('custom-import')
    <script src="{{ URL::asset('js/merchant/bus_create.js') }}"></script>
    <script src="{{ URL::asset('lib/datepicker/bootstrap-datepicker.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::asset('css/merchant/bus_edit.css') }}">
@endsection
@section('title')
    {{ __('merchant_pages.page_bus_edit_title') }}
@endsection

@section('panel_heading')
    @include('merchants.pages.buses.buses_edit_panel')
@endsection

@section('panel_body')
    @component('includes.components.info-box',['info'=> 'Here can stay some info'])@endcomponent
    @include('flash::message')
    @include('includes.errors.message')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>{{__('merchant_pages.page_bus_edit_form_title')}}</h5>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" role="form" method="post" action="{{route('merchant.buses.update',$bus->id)}}" accept-charset="UTF-8" style="padding: 20px">
                @include('merchants.pages.buses.fields_edit')
            </form>
        </div>
        <script type="text/javascript">
            var routes={!! json_encode($routes) !!}
        </script>
    </div>
@endsection