@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_bustype_index_title') }}
@endsection

@section('panel_heading')
    @include('admins.pages.bus_types.bustype_panel')
@endsection

@section('panel_body')
    @include('flash::message')
    <section class="content-header">
        <h3 >{{__('admin_pages.page_bustype_index_form_title')}}</h3>
        {!! $table->render() !!}
    </section>
@endsection