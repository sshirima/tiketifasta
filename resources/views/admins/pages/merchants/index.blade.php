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
            <li class="active">{{__('admin_page_merchants.navigation_link_view')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
            <div class="box box-primary">
                <div class="box-header">
                    <section class="content-header">
                        <h3 class="pull-left">Merchants</h3>
                        <h3 class="pull-right">
                            <a class="btn btn-primary pull-right"
                               href="{{route('admin.merchant.create')}}">Add merchant</a>
                        </h3>
                    </section>
                </div>
                <div class="box-body">
                    @if(count($merchants) <=0)
                        No records
                    @else
                        @include('admins.pages.merchants.table')
                    @endif
                </div>
            </div>
    </section>
@endsection