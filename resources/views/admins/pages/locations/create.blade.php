@extends('admins.layouts.master')

@section('title')
    {{ __('admin_pages.page_locations_title_create') }}
@endsection

@section('panel_heading')
    @include('admins.pages.locations.location_panel')
@endsection

@section('panel_body')
    <section class="content-header">
        <h3>
            {{__('admin_pages.page_locations_create_form_title')}}
        </h3>
    </section>
    <div class="content">
        @include('includes.errors.message')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="container col-md-6 col-md-offset-1">
                        <form class="form-horizontal" role="form" method="post" action="{{route('admin.location.store')}}" accept-charset="UTF-8" style="padding: 20px">
                            @include('admins.pages.locations.fields')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection