@extends('merchants.layouts.master')

@section('title')
    {{ __('merchant_pages.page_title_ticketprice_create') }}
@endsection

@section('panel_heading')
    @include('merchants.pages.buses.buses_edit_panel')
@endsection

@section('panel_body')
    @include('flash::message')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Bus: {{$bus->reg_number}}</h5>
        </div>
        <div class="panel-body">
            @include('includes.errors.message')
            <form class="form-horizontal" role="form" method="post" action="{{route('merchant.ticket_price.store',$subRoute->id)}}" accept-charset="UTF-8">
                @include('merchants.pages.ticket_prices.fields')
            </form>
            {{--{{$subRoute}}--}}
        </div>
    </div>

@endsection