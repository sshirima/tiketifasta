@extends('admins.layouts.master')

@section('title')
    Tickets
@endsection


@section('content-head')
    <section class="content-header">
        <h1>
            Available tickets
            <small>{{__('merchant_page_tickets.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.tickets.index')}}"> {{__('merchant_page_tickets.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('merchant_page_tickets.navigation_link_view')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    @include('flash::message')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.schedules.schedule_panel')
            </div>
            <div class="tab-content">
                {!! $ticketsTable->render() !!}
            </div>
        </div>
    </section>
@endsection