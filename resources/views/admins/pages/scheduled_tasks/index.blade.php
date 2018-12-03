@extends('admins.layouts.master')

@section('title')
    Scheduled tasks
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            Scheduled tasks
            <small> </small>
        </h1>
        {{--<ol class="breadcrumb">
            <li>
                <a href="{{route('admin.schedules.index')}}"> {{__('admin_page_schedules.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_schedules.navigation_link_create')}}</li>
        </ol>--}}
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">

            </div>
            <div class="tab-content">
                {!! $table->render() !!}
            </div>
        </div>
    </section>
@endsection