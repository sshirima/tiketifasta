@extends('merchants.layouts.master')

@section('title')
    {{ __('page_home.page_tile_merchant') }}
@endsection

@section('panel_heading')
    @include('merchants.pages.staff.staff_panel')
@endsection

@section('panel_body')
    <section class="content-header">
        <h3>
            Create staff account
        </h3>
    </section>
    <div class="content">
        @include('includes.errors.message')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="container col-md-6 col-md-offset-1">
                        <form class="form-horizontal" role="form" method="post" action="{{route('merchant.staff.store')}}" accept-charset="UTF-8" style="padding: 20px">
                            @include('merchants.pages.staff.fields')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection