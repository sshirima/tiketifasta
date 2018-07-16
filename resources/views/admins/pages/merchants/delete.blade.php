@extends('admins.layouts.master')

@section('title')
    {{ __('page_home.page_tile_admin') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            {{__('admin_page_merchants.content_header_title')}}
            <small>{{__('admin_page_merchants.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.merchant.index')}}"> {{__('admin_page_merchants.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_merchants.navigation_link_delete')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box box-danger">
            <div class="box-header">
                <h5>
                    Confirm account deletion
                </h5>
            </div>
            <div class="box-body">
                <div class="form-horizontal">
                    {!! Form::open(['route' => ['admin.merchant.remove', $merchant->id], 'method' => 'delete']) !!}
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-8">
                            You are about to delete merchant account which will delete all of it information<br>
                            Are you sure you want to delete?
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-8">
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                            <a href="{!! route('admin.merchant.index') !!}" class="btn btn-default">Cancel</a>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection