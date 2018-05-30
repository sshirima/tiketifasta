@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_approve_bus_route_confirm_title') }}
@endsection

@section('panel_heading')
    @include('admins.pages.approve_timetables.approve_panel')
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-header">
                <h3>{{__('admin_pages.page_approve_bus_route_confirm_form_title')}} </h3>
            </div>
            <div class="box-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4> Confirmation notice </h4>
                    </div>
                    <div class="panel-body">
                        You are about to allow routes of the selected bus to be advertised for the customer to book the tickets<br>
                        Please note that this operation will allow all the disabled timetables that exist for this route<br>
                        Note: After this operation clients will start booking this bus on this particular route
                        <form method="POST" action="{{route('admin.bus-route.authorize',$busRoute->id)}}" accept-charset="UTF-8">
                            <input name="_token" type="hidden" value="{{csrf_token()}}">
                            <div class="form-group">
                                <button class="btn btn-primary col-sm-4" type="submit"><i class="far fa-check-circle"></i> Confirm authorization</button>
                                <a class="col-sm-4" href="{{route('admin.bus-route.approve',$busRoute->id)}}"><span class="btn btn-danger"> <i class="fas fa-arrow-circle-left"></i> Back</span></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection