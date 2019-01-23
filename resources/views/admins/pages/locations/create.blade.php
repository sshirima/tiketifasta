@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_locations_title_create') }}
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            {{__('admin_page_locations.content_header_title_create')}}
            <small>{{__('admin_page_locations.content_header_sub_title')}}</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.location.index')}}"> {{__('admin_page_locations.navigation_link_index')}}</a>
            </li>
            <li class="active">{{__('admin_page_locations.navigation_link_create')}}</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.buses.buses_panel')
            </div>
            <div class="tab-content">
                <div class="row">
                    <div class="container col-md-6 col-md-offset-1">
                        <form class="form-horizontal" role="form" method="post" action="{{route('admin.location.store')}}" accept-charset="UTF-8" style="padding: 20px">
                            @include('admins.pages.locations.fields')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection