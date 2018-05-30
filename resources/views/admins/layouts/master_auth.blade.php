@extends('layouts.master_admin_auth_v2')

@section('header')
    @include('admins.includes.sections.header-auth')
@endsection

@section('content')
    @yield('content-body')
@endsection

@section('footer')
    @include('admins.includes.sections.footer')
@endsection