@extends('admins.layouts.master')

@section('title')
    {{ __('admin_page_accounts.page_title_index') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            {{__('admin_page_accounts.content_header_title')}}
            <small>{{__('admin_page_accounts.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.admin_accounts.index')}}"> {{__('admin_page_accounts.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_accounts.navigation_link_view')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-body">
                {!! $table->render() !!}
            </div>
        </div>
    </section>
@endsection