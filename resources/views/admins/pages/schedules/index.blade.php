@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_schedules_index_title') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            {{__('admin_page_schedules.content_header_title')}}
            <small>{{__('admin_page_schedules.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.schedules.index')}}"> {{__('admin_page_schedules.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_schedules.navigation_link_create')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-header">
                Buses schedules
            </div>
            <div class="box-body">
                {!! $schedulesTable->render() !!}
            </div>
        </div>
    </section>
@endsection