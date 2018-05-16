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
            {{$merchant->name}}
        </h2>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admins.pages.merchants.show_fields')
                    <a href="{!! route('admin.merchant.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection