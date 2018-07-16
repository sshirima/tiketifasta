@extends('admins.layouts.master')

@section('title')
    {{ __('merchant_page_buses.page_title_show') }}
@endsection


@section('content-head')
    <section class="content-header">
        <h1>
            {{__('merchant_page_buses.content_header_title_show')}}
            <small>{{__('merchant_page_buses.content_header_sub_title_show')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.buses.index')}}"> {{__('merchant_page_buses.navigation_link_index')}}</a>
            </li>
            @if(isset($bus))
                <li class="active">{{__('merchant_page_buses.navigation_link_show')}}</li>
            @else
                <li class="active">{{__('merchant_page_buses.navigation_link_show')}}</li>
            @endif

        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.buses.buses_edit_panel')
            </div>
            <div class="tab-content">
                <div class="form-horizontal" style="padding: 20px">
                    <div class="form-group">
                        <div class="col-sm-7">
                            @include('merchants.pages.buses.show_fields_basic_info')
                        </div>
                        <div class="col-sm-5">
                            @include('merchants.pages.buses.show_fields_more_info')
                        </div>
                    </div>
                    <div class="form-group">
                        @include('admins.pages.buses.show_fields_seat_information')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('import_css')
    <link rel="stylesheet" href="{{ URL::asset('css/seat_charts/jquery.seat-charts.css') }}">
@endsection

@section('import_js')
    <script src="{{URL::asset('js/seat_charts/jquery.seat-charts.js')}}"></script>
    <script src="{{ URL::asset('js/merchant/show_bus_seats.js') }}"></script>
    <script type="text/javascript">
        var seats = {!! json_encode($bus->seats) !!};
        var seat_arrangement = {!! json_encode($bus->busType->seat_arrangement) !!};
    </script>
@endsection