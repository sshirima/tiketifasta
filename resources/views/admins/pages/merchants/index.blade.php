@extends('admins.layouts.master')

@section('title')
    {{ __('page_home.page_tile_admin') }}
@endsection

@section('panel_heading')
    <li class="active"><a href="#" data-toggle="tab">View</a></li>
@endsection

@section('panel_body')
    <div class="tab-pane fade in active" id="tab1info">
        <section class="content-header">
            <h2 class="pull-left">Merchants</h2>
            <h1 class="pull-right">
                <a class="btn btn-primary pull-right"
                   href="{{route('admin.merchant.create')}}">Add merchant</a>
            </h1>
        </section>
        <div class="content">
            <div class="clearfix"></div>
            @include('flash::message')
            <div class="clearfix"></div>
            <div class="box box-primary">
                <div class="box-body">
                    @if(count($merchants) <=0)
                        No records
                    @else
                        @include('admins.pages.merchants.table')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection