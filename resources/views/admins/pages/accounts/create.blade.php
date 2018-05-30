@extends('admins.layouts.master')

@section('title')
    {{ __('admin_page_accounts.page_title_create') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            {{__('admin_page_accounts.content_header_title_create')}}
            <small>{{__('admin_page_accounts.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.admin_accounts.index')}}"> {{__('admin_page_accounts.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_accounts.navigation_link_create')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-body">
                @include('admins.pages.accounts.fields')
            </div>
        </div>
    </section>

@endsection