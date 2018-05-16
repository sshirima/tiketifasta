@extends('admins.layouts.master')

@section('title')
    {{ __('page_home.page_tile_admin') }}
@endsection

@section('panel_heading')
    <li class="active"><a href="#" data-toggle="tab">View</a></li>
@endsection

@section('panel_body')
    <section class="content-header">
        <h2>
            Create merchant account
        </h2>
    </section>
    <div class="content">
        @include('includes.errors.message')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.merchant.store']) !!}
                    @include('admins.pages.merchants.fields')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection