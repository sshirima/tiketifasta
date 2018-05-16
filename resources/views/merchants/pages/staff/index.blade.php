@extends('merchants.layouts.master')

@section('title')
    {{ __('page_home.page_tile_merchant') }}
@endsection

@section('panel_heading')
    @include('merchants.pages.staff.staff_panel')
@endsection

@section('panel_body')
    @include('flash::message')
    <section class="content-header">
        <h3 >Staff information</h3>
        {!! $table->render() !!}
    </section>

@endsection