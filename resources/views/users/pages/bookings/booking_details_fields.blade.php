<h3 class="mb-5">Customer information</h3>
<form role="form" method="get"
      action="{{route('booking.store', [$trip->bus->id,$trip->schedule_id,$trip->trip_id])}}"
      accept-charset="UTF-8">
    <div class="row">
        <strong class="col-sm-4 control-label">Title </strong>
        <div class="col-sm-8">
            {!! Form::select('title',['mr'=>'Mr.','mrs'=>'Mrs.','miss'=>'Miss.','rev'=>'Rev.','dr'=>'Dr.'] ,null, ['class' => 'form-control']) !!}
        </div>
    </div><br>
    <div class="row">
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
    </div><br>
    <div class="row">
        <strong class="col-sm-4">Phone number: </strong>
        <div class="col-sm-8">
            {!! Form::text('phonenumber',old('phonenumber'), ['class' => 'form-control', 'required', 'placeholder'=>'Type phone number...']) !!}
        </div>
    </div><br>
    <div class="row">
        <strong class="col-sm-4">Email: </strong>
        <div class="col-sm-8">
            {!! Form::email('email',old('email'), ['class' => 'form-control', 'required','placeholder'=>'Type email...']) !!}
        </div>
    </div><br>
    <div class="row">
        <strong class="col-sm-4">Payment via: </strong>
        <div class="col-sm-8">
            {!! Form::select('payment',$paymentOptions,old('payment'), ['class' => 'form-control']) !!}
        </div>
    </div><br>
    <div class="row">
        <strong class="col-sm-4"></strong>
        <div class="col-sm-8">
            <div class="checkbox">
                <label class="icheckbox_square-blue" >
                    <input name="agree_terms" type="checkbox" value="remember">
                    {{ __('merchant_page_auth_login.label_agree_terms_conditions') }}
                </label>
            </div>
        </div>
    </div><br>
    <div class="row">
        <strong class="col-sm-4"> </strong>
        <div class="col-sm-8">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div><br>
    @csrf
    <input hidden name="seat" value="{{$trip->bus->seat_name}}">
{!! Form::close() !!}