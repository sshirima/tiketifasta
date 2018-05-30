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
            <li class="active">{{__('admin_page_merchants.navigation_link_create')}}</li>
        </ol>
    </section>
@endsection
@section('content-body')

    <section class="content container-fluid">
        <form class="form-horizontal" method="POST" action="{{route('admin.merchant.store')}}" accept-charset="UTF-8">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @include('admins.pages.merchants.fields')
        </form>
    </section>
@endsection