@extends('layouts.master')

@section('header')
    @include('merchants.includes.header.master')
@endsection

@section('nav-bar-horizontal')

@endsection

@section('content')
    @include('merchants.includes.body.navigation')
    @include('includes.body.panel')
@endsection

@section('footer')
    @include('layouts.footer')
@endsection