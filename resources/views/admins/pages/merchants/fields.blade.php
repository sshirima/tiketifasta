
<!-- Name Field -->


    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">Merchant information</div>
            <div class="panel-body">
                {!! Form::label('name', 'Merchant name:') !!}
                {!! Form::text('name', old('name'), ['class' => 'form-control','placeholder'=>'Merchant name']) !!}
            </div>
        </div>
    </div>

<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">Default account information</div>
        <div class="panel-body">
            {!! Form::label('firstname', 'First name:') !!}
            {!! Form::text('firstname', old('firstname'), ['class' => 'form-control', 'placeholder'=>'Type account first name...']) !!}

            {!! Form::label('lastname', 'Last name:') !!}
            {!! Form::text('lastname', old('lastname'), ['class' => 'form-control', 'placeholder'=>'Type account last name...']) !!}

            {!! Form::label('email', 'Email:') !!}
            {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder'=>'Type account email address...']) !!}

            {!! Form::label('phonenumber', 'Phone number:') !!}
            {!! Form::text('phonenumber', old('phonenumber'), ['class' => 'form-control', 'placeholder'=>'Type account phone number...']) !!}

            {!! Form::label('password', 'Password:') !!}
            {!! Form::password('password',['class' => 'form-control', 'placeholder'=>'Type default password']) !!}

            {!! Form::label('password_confirmation', 'Confirm password:') !!}
            {!! Form::password('password_confirmation',['class' => 'form-control', 'placeholder'=>'Re-type default password']) !!}
        </div>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.merchant.index') !!}" class="btn btn-default">Cancel</a>
</div>

