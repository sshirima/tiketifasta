@extends('admins.layouts.master')

@section('title')
    {{ __('admin_page_merchants.page_title_edit') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            {{__('admin_page_merchants.content_header_title_edit')}}
            <small>{{__('admin_page_merchants.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.merchant.index')}}"> {{__('admin_page_merchants.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_merchants.navigation_link_edit')}}</li>
        </ol>
    </section>
@endsection
@section('content-body')

    <section class="content container-fluid">
        @if(isset($merchant))
            {!! Form::open(['route' => ['admin.merchant.update', $merchant->id], 'method' => 'put','class'=>'form-horizontal']) !!}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h5>Merchant information</h5>
                    </div>

                    <div class="box-body">
                        <div class="form-group">
                            {!! Form::label('name', 'Merchant name:',['class'=>'control-label col-sm-3','for'=>'name']) !!}
                            <div class="col-sm-4">
                                {!! Form::text('name', $merchant->name, ['class' => 'form-control','placeholder'=>'Merchant name']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('contract_start', 'Start of contract:',['class'=>'control-label col-sm-3','for'=>'contract_start']) !!}
                            <div class="col-sm-4">
                                <input class="form-control" type="date" id="contract_start" name="contract_start" value="{{$merchant->contract_start}}">
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('contract_end', 'End of contract:',['class'=>'control-label col-sm-3','for'=>'contract_end']) !!}
                            <div class="col-sm-4">
                                <input class="form-control" type="date" id="contract_end" name="contract_end" value="{{$merchant->contract_end}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4"></label>
                            <div class="col-sm-6">
                                {!! Form::submit('Update information', ['class' => 'btn btn-primary']) !!}
                                <a href="{!! route('admin.merchant.index') !!}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        @else
            <div class="label label-warning"> Can not found merchant with the given id</div>
        @endif
    </section>
@endsection