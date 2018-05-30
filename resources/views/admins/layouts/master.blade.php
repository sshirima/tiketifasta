@extends('layouts.master_admin_v2')

@section('header')
    @include('admins.includes.sections.header')
@endsection

@section('sidebar-left')
    @include('admins.includes.sections.sidebar-left')
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
    @yield('content-head')
    <!-- Operation status -->
        <div style="padding: 10px">
            @include('flash::message')
            @include('includes.errors.message')
        </div>
        <!-- Main content -->
    @yield('content-body')
    <!-- /.content -->
    </div>
@endsection

@section('footer')
    @include('admins.includes.sections.footer')
@endsection