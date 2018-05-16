@extends('merchants.layouts.master')

@section('title')
    {{ __('merchant_pages.page_title_ticketprice_index') }}
@endsection

@section('panel_heading')
    @include('merchants.pages.buses.buses_edit_panel')
@endsection

@section('panel_body')
    <section>
        <h4>Prices for the tickets</h4>
    </section>
    @include('flash::message')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Bus: {{$bus->reg_number}}</h5>
        </div>
        <div class="panel-body">
            @include('includes.errors.message')
            {!! $table->render() !!}
        </div>
    </div>
@endsection