@extends('admins.layouts.master')

@section('custom-import')
    <link rel="stylesheet" href="{{ URL::asset('css/seat_charts/jquery.seat-charts.css') }}">
    <script src="{{URL::asset('js/seat_charts/jquery.seat-charts.js')}}"></script>
    <script src="{{ URL::asset('js/admin/bustype_create.js') }}"></script>
@endsection

@section('title')
    {{ __('admin_pages.page_bustype_create_title') }}
@endsection

@section('panel_heading')
    @include('admins.pages.bus_types.bustype_panel')
@endsection

@section('panel_body')
    <section class="content-header">
        <h3>
            {{__('admin_pages.page_bustype_create_form_title')}}
        </h3>
    </section>
    <div class="content">
        @include('includes.errors.message')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="container col-md-12">
                        <form class="form-horizontal" role="form" method="post" action="{{route('admin.bustype.store')}}" accept-charset="UTF-8" style="padding: 20px">
                            @include('admins.pages.bus_types.fields')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection