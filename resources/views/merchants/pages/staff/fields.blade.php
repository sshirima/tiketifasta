<div class="form-group">
    {!! Form::label('firstname', 'First name:', ['class'=>'col-sm-5 control-label', 'for'=>'firstname']) !!}
    <div class="col-sm-7">
        {!! Form::text('firstname', old('firstname'), ['class' => 'form-control', 'placeholder'=>'Type account first name...']) !!}
    </div>

</div>

<div class="form-group">
    {!! Form::label('lastname', 'Last name:', ['class'=>'col-sm-5 control-label']) !!}
    <div class="col-sm-7">
        {!! Form::text('lastname', old('lastname'), ['class' => 'form-control', 'placeholder'=>'Type account last name...']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('email', 'Email:', ['class'=>'col-sm-5 control-label']) !!}
    <div class="col-sm-7">
        {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder'=>'Type account email address...']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('phonenumber', 'Phone number:', ['class'=>'col-sm-5 control-label']) !!}
    <div class="col-sm-7">
        {!! Form::text('phonenumber', old('phonenumber'), ['class' => 'form-control', 'placeholder'=>'Type account phone number...']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('password', 'Password:', ['class'=>'col-sm-5 control-label']) !!}
    <div class="col-sm-7">
        {!! Form::password('password',['class' => 'form-control', 'placeholder'=>'Type default password']) !!}
    </div>

</div>

<div class="form-group">
    {!! Form::label('password_confirmation', 'Confirm password:', ['class'=>'col-sm-5 control-label']) !!}
    <div class="col-sm-7">
        {!! Form::password('password_confirmation',['class' => 'form-control', 'placeholder'=>'Re-type default password']) !!}
    </div>
</div>
@csrf
<div class="form-group">
    <div class="col-sm-7 col-md-offset-5">
        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('merchant.staff.index') !!}" class="btn btn-default">Cancel</a>
    </div>
</div>
