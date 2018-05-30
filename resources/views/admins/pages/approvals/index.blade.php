@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_approvals_index_title') }}
@endsection

@section('content-body')

    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.approvals.approval_panel')
            </div>
            <div class="tab-content">

            </div>
        </div>
    </section>
@endsection