@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_approvals_reassign_schedule_title') }}
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.approvals.approval_panel')
            </div>
            <div class="tab-content">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form class="navbar-form">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Search" name="date" id="date" type="date" value="{{old('date')}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{Form::select('route_id',$routes,old('route_id'),['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{Form::select('merchant_id',$merchants,old('merchant_id'),['class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-primary pull-right" type="submit"><i class="glyphicon glyphicon-refresh"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="panel-body">
                        {!! $table->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection