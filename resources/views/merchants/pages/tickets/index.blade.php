@extends('merchants.layouts.master')

@section('title')
    {{ __('merchant_pages.page_title_bus_seats') }}
@endsection

@section('panel_heading')
    @include('merchants.pages.buses.buses_edit_panel')
@endsection

@section('panel_body')
    @include('flash::message')
    <section class="content-header">
        <h2 class="pull-left">{{$bus->reg_number}} seats</h2>
        @if($bus->seats()->count() == 0)
            <h2 class="pull-right">
                <a class="btn btn-primary pull-right"
                   href="{{route('merchant.bus.seats.create',$bus->id)}}">Generate seats</a>
            </h2>
        @endif
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @if($bus->seats()->count() == 0)
                    Currently no seats exists for this bus, please click on generate button to generate the seats
                @else
                    {!! $table->render() !!}
                @endif
            </div>
        </div>
    </div>

@endsection