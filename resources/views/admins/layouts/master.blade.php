@extends('layouts.master')

@section('header')
    @include('admins.includes.header.master')
@endsection

@section('nav-bar-horizontal')

@endsection

@section('content')
    @include('admins.includes.body.navigation')
    @include('includes.body.panel')
@endsection

@section('footer')
    @include('layouts.footer')
@endsection