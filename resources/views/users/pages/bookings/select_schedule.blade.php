@extends('users.layouts.master-v2')

@section('title')
    Home
@endsection

@section('contents')
    <div class="row">
        <div class="col-md-12 intro-section-text-wrap">
            <h2 id="intro_section_text_1" class="intro">
                Are you travelling?
            </h2>
        </div>
    </div>
    @include('includes.errors.message')
    <form class="form-horizontal" role="form" method="get"
          action="{{route('booking.select.bus')}}" accept-charset="UTF-8" style="padding: 20px">
        <div class="row">

            <div class="col-md-12 intro-section-text-wrap">
                <div id="intro-section" class="intro-section">
                    {!! Form::label('trip_type', 'Trip: ', ['class'=>'col-sm-1 control-label', 'for'=>'trip_type']) !!}
                    <div class="col-sm-2">
                        {!! Form::select('trip_type',['one_way'=>'One way'/*,'round_trip'=>'Round trip'*/] ,null, ['class' => 'form-control',]) !!}
                    </div>
                </div>
                <div id="intro-section" class="intro-section">
                    {!! Form::label('departing_date', 'Date: ', ['class'=>'col-sm-1 control-label', 'for'=>'departing_date']) !!}
                    <div class="col-sm-2">
                        {!! Form::date('departing_date',null, ['class' => 'form-control',]) !!}
                    </div>
                </div>
                <div id="intro-section" class="intro-section">
                    {!! Form::label('start_location', 'From: ', ['class'=>'col-sm-1 control-label', 'for'=>'start_location']) !!}
                    <div class="col-sm-2">
                        {!! Form::select('start_location',$sources,null, ['class' => 'form-control',]) !!}
                    </div>
                </div>
                <div id="intro-section" class="intro-section">
                    {!! Form::label('destination', 'To: ', ['class'=>'col-sm-1 control-label', 'for'=>'start_location']) !!}
                    <div class="col-sm-2">
                        {!! Form::select('destination',$destinations,null, ['class' => 'form-control',]) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 intro-section-text-wrap">
                <div id="intro-section" class="intro-section">
                    <button type="submit" data-anchor=""
                            class="btn btn-primary standard-button inpage-scroll inpage_scroll_btn">
                        <span class="screen-reader-text">Header button label:Download Now</span> Check availability
                    </button>
                </div>
            </div>
        </div>
    </form>
    @include('users.includes.contents.about')
    @include('users.includes.contents.services')

    @include('users.includes.contents.contact-info')
    @include('users.includes.contents.testimonials')
@endsection