@extends('layouts.master')

@section('custom-import')
    <link rel="stylesheet" href="{{ URL::asset('css/users/pages/login/forms/signin.css') }}">
@endsection

@section('header')
    @include('admins.includes.header.auth')
@endsection

@section('content')
    @yield('contents')
@endsection

@section('footer')
    @include('layouts.footer')
@endsection