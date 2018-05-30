@extends('layouts.master_merchant_auth_v2')

@section('header')
    @include('merchants.includes.sections.header-auth')
@endsection

@section('content')
    @yield('content-body')
@endsection

@section('footer')
    @include('merchants.includes.sections.footer')
@endsection