
<!-- Name Field -->
<div class="box box-danger">
    <div class="box-header with-border">
        <h4>Merchant information</h4>
    </div>

    <div class="box-body">
        <div class="form-group">
            {!! Form::label('name', 'Merchant name:',['class'=>'control-label col-sm-3','for'=>'name']) !!}
            <div class="col-sm-4">
                {!! Form::text('name', old('name'), ['class' => 'form-control','placeholder'=>'Merchant name']) !!}
            </div>
        </div>
    </div>
</div>

<div class="box box-danger">
    <div class="box-header with-border"><h4>Default account information</h4></div>
    <div class="box-body">
        <div class="form-group">
            {!! Form::label('firstname', 'First name:',['class'=>'control-label col-sm-3','for'=>'firstname']) !!}
            <div class="col-sm-4">
                {!! Form::text('firstname', old('firstname'), ['class' => 'form-control', 'placeholder'=>'Type account first name...']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('lastname', 'Last name:',['class'=>'control-label col-sm-3','for'=>'lastname']) !!}
            <div class="col-sm-4">
                {!! Form::text('lastname', old('lastname'), ['class' => 'form-control', 'placeholder'=>'Type account last name...']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('email', 'Email:',['class'=>'control-label col-sm-3','for'=>'email']) !!}
            <div class="col-sm-4">
                {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder'=>'Type account email address...']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('phonenumber', 'Phone number:',['class'=>'control-label col-sm-3','for'=>'phonenumber']) !!}
            <div class="col-sm-4">
                {!! Form::text('phonenumber', old('phonenumber'), ['class' => 'form-control', 'placeholder'=>'Type account phone number...']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('password', 'Password:',['class'=>'control-label col-sm-3','for'=>'email']) !!}
            <div class="col-sm-4">
                {!! Form::password('password',['class' => 'form-control', 'placeholder'=>'Type default password']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('password_confirmation', 'Confirm password:',['class'=>'control-label col-sm-3','for'=>'password_confirmation']) !!}
            <div class="col-sm-4">
                {!! Form::password('password_confirmation',['class' => 'form-control', 'placeholder'=>'Re-type default password']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3"></label>
            <div class="col-sm-6">
                {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                <a href="{!! route('admin.merchant.index') !!}" class="btn btn-default">Cancel</a>
            </div>
        </div>

    </div>
</div>

