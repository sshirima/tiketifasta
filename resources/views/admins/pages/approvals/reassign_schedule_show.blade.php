@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_approvals_reassign_schedule_title') }}
@endsection

@section('panel_heading')
    @include('admins.pages.approvals.approval_panel')
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.approvals.approval_panel')
            </div>
            <div class="tab-content">
                @if(session()->has('bookingTable'))
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Customers to be informed of the change
                        </div>
                        <div class="panel-body">
                            {!! $bookingTable->render() !!}
                            <form method="POST" class="form-horizontal"
                                  action="{{route('admin.approvals.reassigned-schedules.confirm',[$reassignedBus->reassignedSchedule->id])}}"
                                  accept-charset="UTF-8">
                                <input name="_token" type="hidden" value="{{csrf_token()}}">
                                <input name="reassigned_bus" type="hidden" value="{{$reassignedBus->reassignedSchedule->id}}">
                                <div class="form-group">
                                    <label for="reassign_comment" class="col-sm-2">Comments:</label>
                                    <div class="col-sm-6">
                        <textarea name="reassign_comment" id="reassign_comment" class="form-control"
                                  placeholder="Re-assignment comments"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1">
                                        <a href="{{route('admin.approvals.reassigned-schedules.')}}"><span
                                                    class="btn btn-danger"> <i class="fas fa-arrow-circle-left"></i> Back</span></a>
                                    </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-primary" type="submit"><i class="far fa-check-circle"></i> Confirm re-assignment</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <h4> Canceled bus</h4>
                                </div>
                                <div class="panel-body">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td>Reg number:</td>
                                            <td>{{$dailyTimetable->busRoute->bus->reg_number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Number of seats:</td>
                                            <td>{{$dailyTimetable->busRoute->bus->busType->seats}}</td>
                                        </tr>
                                        <tr>
                                            <td>Bookings:</td>
                                            <td>{{count($dailyTimetable->bookings)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Class:</td>
                                            <td>{{$dailyTimetable->busRoute->bus->class}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h4> Re-assigned bus</h4>
                                </div>
                                <div class="panel-body">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td>Reg number:</td>
                                            <td>{{$reassignedBus->reg_number}}</td>
                                        </tr>
                                        <tr>
                                            <td>Merchant name:</td>
                                            <td>{{$reassignedBus->merchant->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>Available of seats:</td>
                                            <td>{{$reassignedBus->busType->seats}}</td>
                                        </tr>
                                        <tr>
                                            <td>Class:</td>
                                            <td>{{$reassignedBus->class}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-warning"><strong>{{count($dailyTimetable->bookings)}}</strong> customer will be notified via SMS regarding the change </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4> {{__('merchant_pages.page_oos_reassign_confirm_panel_title')}}</h4>
                        </div>
                        <div class="panel-body">
                            <form method="GET" class="form-horizontal"
                                  action="{{route('admin.approvals.reassigned-schedules.bookings',[$reassignedBus->reassignedSchedule->id])}}"
                                  accept-charset="UTF-8">
                                <input name="_token" type="hidden" value="{{csrf_token()}}">
                                <input name="reassigned_bus" type="hidden" value="{{$reassignedBus->reassignedSchedule->id}}">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        You are about to confirm re-assignment to all of the booking on cancelled bus to another bus<br>
                                        Please note that this operation remove all the booking of this bus and assign them to another bus<br>
                                        Note: This operation is subject to <strong>cost</strong>, as per the agreed term and conditions<br>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-2">
                                        <a href="{{route('admin.approvals.reassigned-schedules')}}"><span
                                                    class="btn btn-danger"> <i class="fas fa-arrow-circle-left"></i> Back</span></a>
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary" type="submit"><i class="far fa-check-circle"></i> Next</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection