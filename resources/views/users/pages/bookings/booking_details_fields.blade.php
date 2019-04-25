<form role="form" method="get"
      action="{{route('booking.store', [$trip->bus->id,$trip->schedule_id,$trip->trip_id])}}"
      accept-charset="UTF-8" style="padding: 10px">
    <div class="row">
        <div class="col-sm-2 control-label"><b class="pull-right">@lang('Names')</b> </div>
        <div class="col-sm-2">
            {!! Form::select('title',[0=>__('Select Title'),'mr'=>__('Mr'),'mrs'=>__('Mrs'),'miss'=>__('Miss'),'rev'=>__('Rev'),'dr'=>__('Dr')] ,null, ['class' => 'form-control']) !!}
        </div>
        <div class="col-sm-4">
            {!! Form::text('firstname',old('firstname'), ['class' => 'form-control', 'required', 'placeholder'=>__('First name')]) !!}
        </div>
        <div class="col-sm-4">
            {!! Form::text('lastname',old('lastname'), ['class' => 'form-control', 'required', 'placeholder'=>__('Last name')]) !!}
        </div>
    </div>
    {{--<div class="row">
        <strong class="col-sm-4 control-label">First name: </strong>
        <div class="col-sm-8">
            {!! Form::text('firstname',old('firstname'), ['class' => 'form-control', 'required', 'placeholder'=>'Type first name...']) !!}
        </div>
    </div><br>
    <div class="row">
        <strong class="col-sm-4">Last name: </strong>
        <div class="col-sm-8">
            {!! Form::text('lastname',old('lastname'), ['class' => 'form-control', 'required', 'placeholder'=>'Type last name...']) !!}
        </div>
    </div>--}}
    <br>
    <div class="row">
        <div class="col-sm-2"><b class="pull-right">@lang('Contacts')</b> </div>
        <div class="col-sm-5">
            {!! Form::text('phonenumber',old('phonenumber'), ['class' => 'form-control', 'required', 'placeholder'=>__('Phone number')]) !!}
        </div>
        <div class="col-sm-5">
            {!! Form::email('email',old('email'), ['class' => 'form-control', 'placeholder'=>__('Email address')]) !!}
        </div>
    </div><br>
    {{--<div class="row">
        <strong class="col-sm-4">Email: </strong>
        <div class="col-sm-8">
            {!! Form::email('email',old('email'), ['class' => 'form-control', 'required','placeholder'=>'Type email...']) !!}
        </div>
    </div><br>--}}
    <div class="row">
        <div class="col-sm-2"><b class="pull-right">@lang('Stations')</b></div>
        <div class="col-sm-5">
            {!! Form::select('boarding_point',$boarding_points,old('dropping_point'), ['class' => 'form-control']) !!}
        </div>
        <div class="col-sm-5">
            {!! Form::select('dropping_point',$dropping_points,old('dropping_point'), ['class' => 'form-control']) !!}
        </div>
    </div><br>
    <div class="row">
        <strong class="col-sm-2"><b class="pull-right">@lang('Payment')</b> </strong>
        <div class="col-sm-5">
            {!! Form::select('payment',$paymentOptions,old('payment'), ['class' => 'form-control']) !!}
        </div>
    </div><br>
    <div class="row">
        <strong class="col-sm-2"></strong>
        <div class="col-sm-10">
            <div class="checkbox">
                <label class="icheckbox_square-blue" >
                    <input name="agree_terms" type="checkbox" value="remember">
                    {{ __('I have read and agree to our Terms and Conditions and Refund Policy') }}
                </label>
            </div>
        </div>
    </div><br>
    <div class="row">
        <strong class="col-sm-2"> </strong>
        <div class="col-sm-5">
            <button type="submit" class="btn " style="background-color: #17a2b8"><span style="color: white">@lang('Submit')</span></button>
        </div>
    </div><br>
    @csrf
    <input hidden name="seat" value="{{$trip->bus->seat_name}}">
{!! Form::close() !!}