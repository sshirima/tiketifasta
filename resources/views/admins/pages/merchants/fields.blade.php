
<!-- Name Field -->
<div class="box box-primary">
    <div class="box-header with-border">
        <h5>Merchant information</h5>
    </div>

    <div class="box-body">
        <div class="form-group">
            {!! Form::label('name', 'Merchant name:',['class'=>'control-label col-sm-3','for'=>'name']) !!}
            <div class="col-sm-4">
                {!! Form::text('name', old('name'), ['class' => 'form-control','placeholder'=>'Merchant name']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('contract_start', 'Start of contract:',['class'=>'control-label col-sm-3','for'=>'contract_start']) !!}
            <div class="col-sm-4">
                <input class="form-control" type="date" id="contract_start" name="contract_start"}>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('contract_end', 'End of contract:',['class'=>'control-label col-sm-3','for'=>'contract_end']) !!}
            <div class="col-sm-4">
                <input class="form-control" type="date" id="contract_end" name="contract_end">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border"><h5>Default account information</h5></div>
            <div class="box-body">
                <div class="form-group">
                    {!! Form::label('email', 'Email:',['class'=>'control-label col-sm-3','for'=>'email']) !!}
                    <div class="col-sm-8">
                        {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder'=>'Type account email address...']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('phonenumber', 'Phone number:',['class'=>'control-label col-sm-3','for'=>'phonenumber']) !!}
                    <div class="col-sm-8">
                        {!! Form::text('phonenumber', old('phonenumber'), ['class' => 'form-control', 'placeholder'=>'Type account phone number...']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border"><h5>Payment information</h5></div>
            <div class="box-body">
                <div class="form-group">
                    {!! Form::label('payment_mode', __('admin_pages.page_payment_method_fields_label_name'), ['class'=>'col-sm-5 control-label', 'for'=>'payment_mode']) !!}
                    <div class="col-sm-7">
                        {{Form::select('payment_mode',[0=>'Select payment method','mpesa'=>'M-PESA','tigopesa'=>'TIGO-PESA','airtelmoney'=>'AIRTEL-MONEY'],null,['class'=>'form-control'])}}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('account_number', __('admin_pages.page_payment_account_fields_label_name'), ['class'=>'col-sm-5 control-label', 'for'=>'account_number']) !!}
                    <div class="col-sm-7">
                        {!! Form::number('account_number', old('account_number'), ['class' => 'form-control', 'placeholder'=>__('admin_pages.page_payment_account_fields_input_name_placeholder')]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="col-sm-3"></label>
            <div class="col-sm-6">
                {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                <a href="{!! route('admin.merchant.index') !!}" class="btn btn-default">Cancel</a>
            </div>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>


