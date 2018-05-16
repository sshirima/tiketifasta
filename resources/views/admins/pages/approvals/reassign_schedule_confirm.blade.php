@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_approvals_reassign_schedule_title') }}
@endsection

@section('panel_heading')
    @include('admins.pages.approvals.approval_panel')
@endsection

@section('panel_body')
    @include('flash::message')
    @component('includes.components.info-box',['info'=> 'Here can stay some info'])@endcomponent
    {{--<section class="content-header">
        <h3>{{__('admin_pages.page_approvals_reassign_schedule_form_title')}} </h3>
    </section>--}}
   @if(session()->has('messageSuccess'))
       <div class="alert alert-success">{{session()->get('messageSuccess')}}</div>
       <div class="alert alert-success">{{session()->get('messageSMS')}}</div>
       <div class="panel panel-default">
           <div class="panel-heading">
               <h4>Customers to be informed of the change</h4>
           </div>
           <div class="panel-body">
               {!! $bookingTable->render() !!}
           </div>
       </div>
   @else
       <div class="panel panel-default">
           <div class="panel-body">
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
                       <div class="col-sm-2">
                           <a href="{{route('admin.approvals.reassigned-schedules.bookings',[$reassignedBus->reassignedSchedule->id])}}"><span
                                       class="btn btn-danger"> <i class="fas fa-arrow-circle-left"></i> Back</span></a>
                       </div>
                       <div class="col-sm-2">
                           <button class="btn btn-primary" type="submit"><i class="far fa-check-circle"></i> Confirm re-assignment</button>
                       </div>
                   </div>
               </form>
           </div>
       </div>
       <div class="panel panel-default">
           <div class="panel-heading">
               <h4>Customers to be informed of the change</h4>
           </div>
           <div class="panel-body">
               {!! $bookingTable->render() !!}

           </div>
       </div>
   @endif


@endsection