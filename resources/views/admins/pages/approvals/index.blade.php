@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_approvals_index_title') }}
@endsection

@section('panel_heading')
    @include('admins.pages.approvals.approval_panel')
@endsection

@section('panel_body')
    @include('flash::message')
    @component('includes.components.info-box',['info'=> 'Here can stay some info'])@endcomponent
    <section class="content-header">
        <h3>{{__('admin_pages.page_approvals_index_form_title')}} </h3>
    </section>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4> Pending approvals</h4>
        </div>

    </div>
@endsection