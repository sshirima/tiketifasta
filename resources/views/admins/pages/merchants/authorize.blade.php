@extends('admins.layouts.master')

@section('title')
    {{ __('admin_page_merchants.page_title_authorize') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            {{__('admin_page_merchants.content_header_title_authorize')}}
            <small>{{__('admin_page_merchants.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.merchant.index')}}"> {{__('admin_page_merchants.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_merchants.navigation_link_show')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-body">
                @if(isset($merchant))
                    <div class="form-horizontal">
                        <div class="form-group">
                            {!! Form::label('name', 'Merchant name:',['class'=>'control-label col-sm-3','for'=>'name']) !!}
                            <div class="col-sm-4">
                                <input class="form-control" value="{{$merchant->name}}" disabled>
                            </div>
                        </div>
                        @if(isset($merchant->contract_start) && isset($merchant->contract_end) && (new DateTime($merchant->contract_end) > new DateTime('now')))
                            <div class="form-group">
                                {!! Form::label('contract_start', 'Start of contract:',['class'=>'control-label col-sm-3','for'=>'contract_start']) !!}
                                <div class="col-sm-4">
                                    <input class="form-control" value="{{$merchant->contract_start}}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('contract_end', 'End of contract:',['class'=>'control-label col-sm-3','for'=>'contract_end']) !!}
                                <div class="col-sm-4">
                                    <input class="form-control" value="{{$merchant->contract_end}}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('contract_remaining_days', 'Remaining days:',['class'=>'control-label col-sm-3','for'=>'contract_remaining_days']) !!}
                                <div class="col-sm-4">
                                    <h4 ><span class="label label-default">{{$merchant->contract_days}} days</span></h4>
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('contract_remaining_days', 'Contract status:',['class'=>'control-label col-sm-3','for'=>'contract_remaining_days']) !!}
                                <div class="col-sm-4">
                                    @if($merchant->contract_status)
                                        <div class="label label-success"> Contract active</div>
                                    @else
                                        <div class="label label-danger"> Contract expired </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('status', 'Account status:',['class'=>'control-label col-sm-3','for'=>'status']) !!}
                                <div class="col-sm-4">
                                    @if($merchant->status)
                                        <div class="label label-success"> Active </div>
                                    @else
                                        <div class="label label-danger"> Inactive </div>
                                    @endif
                                </div>
                                <div class="col-sm-4">
                                    {!! Form::open(['route' => [$merchant->status?'admin.merchant.disable':'admin.merchant.enable', $merchant->id], 'method' => 'post','class'=>'form-horizontal']) !!}
                                    @if($merchant->status)
                                        <button class="btn btn-danger" type="submit"> Disable account </button>
                                    @else
                                        <button class="btn btn-success" type="submit"> Enable account </button>
                                    @endif
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        @else
                            {!! Form::open(['route' => [$merchant->status?'admin.merchant.edit':'admin.merchant.edit', $merchant->id], 'method' => 'get','class'=>'form-horizontal']) !!}
                            <div class="form-group">
                                <div class="col-sm-12 text-center">
                                    Merchant contract has expired or not set, please update merchant info
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 text-center">
                                    <button class="btn btn-success" type="submit"> Update merchant </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        @endif

                    </div>
                @else
                    <div class="label label-warning"> Can not found merchant with the given id</div>
                @endif
            </div>
        </div>
    </section>
@endsection