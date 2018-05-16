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
            Confirm account deletion
        </h2>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => ['admin.merchant.remove', $merchant->id], 'method' => 'delete']) !!}
                    <div class="container">
                        You are about to delete merchant account which will delete all of it information<br>
                        Are you sure you want to delete?
                    </div>
                    <br>
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    <a href="{!! route('admin.merchant.index') !!}" class="btn btn-default">Cancel</a>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection