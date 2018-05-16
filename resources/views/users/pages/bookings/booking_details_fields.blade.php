<div class="form-group col-sm-12">
    {!! Form::label('title', 'Title: ', ['class'=>'control-label col-sm-5', 'for'=>'title']) !!}
    <div class="col-sm-7">
        {!! Form::select('title',['mr'=>'Mr.','mrs'=>'Mrs.','miss'=>'Miss.','rev'=>'Rev.','dr'=>'Dr.'] ,null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group  col-sm-12">
    {!! Form::label('firstname', 'First name: ', ['class'=>'col-sm-5 control-label', 'for'=>'firstname']) !!}
    <div class="col-sm-7">
        {!! Form::text('firstname',null, ['class' => 'form-control', 'placeholder'=>'Type first name...']) !!}
    </div>
</div>

<div class="form-group  col-sm-12">
    {!! Form::label('lastname', 'Last name: ', ['class'=>'col-sm-5 control-label', 'for'=>'lastname']) !!}
    <div class="col-sm-7">
        {!! Form::text('lastname',null, ['class' => 'form-control', 'placeholder'=>'Type last name...']) !!}
    </div>
</div>
<div class="form-group  col-sm-12">
    {!! Form::label('phonenumber', 'Phone number: ', ['class'=>'col-sm-5 control-label', 'for'=>'phonenumber']) !!}
    <div class="col-sm-7">
        {!! Form::text('phonenumber',null, ['class' => 'form-control', 'placeholder'=>'Type phone number...']) !!}
    </div>
</div>
<div class="form-group  col-sm-12">
    {!! Form::label('email', 'Email: ', ['class'=>'col-sm-5 control-label', 'for'=>'email']) !!}
    <div class="col-sm-7">
        {!! Form::text('email',null, ['class' => 'form-control', 'placeholder'=>'Type email...']) !!}
    </div>
</div>
<input hidden name="seat" value="{{$schedule->seat}}">
<div class="form-group  col-sm-12">
    {!! Form::label('payment', 'Email: ', ['class'=>'col-sm-5 control-label', 'for'=>'payment']) !!}
    <div class="col-sm-7">
        {!! Form::select('payment',['mpesa'=>'M-pesa','tigo'=>'Tigo-Pesa'],null, ['class' => 'form-control']) !!}
    </div>
</div>