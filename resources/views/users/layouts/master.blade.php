@extends('layouts.master')

@section('header')
    @include('users.includes.header.master')
@endsection

@section('nav-bar-horizontal')

@endsection

@section('content')
    @yield('contents')
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
