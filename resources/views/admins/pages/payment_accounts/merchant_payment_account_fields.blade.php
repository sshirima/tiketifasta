<div class="form-group">
    {!! Form::label('payment_mode', __('admin_pages.page_payment_method_fields_label_name'), ['class'=>'col-sm-5 control-label', 'for'=>'payment_mode']) !!}
    <div class="col-sm-7">
        {{Form::select('payment_mode',[0=>'Select payment method','mpesa'=>'M-PESA','tigopesa'=>'TIGO-PESA','airtelmoney'=>'AIRTEL-MONEY'],isset($account)?$account->payment_method:null,['class'=>'form-control'])}}
    </div>
</div>
<div class="form-group">
    {!! Form::label('account_number', __('admin_pages.page_payment_account_fields_label_name'), ['class'=>'col-sm-5 control-label', 'for'=>'account_number']) !!}
    <div class="col-sm-7">
        {!! Form::number('account_number', isset($account)?$account->account_name: old('account_number'), ['class' => 'form-control', 'placeholder'=>__('admin_pages.page_payment_account_fields_input_name_placeholder')]) !!}
    </div>
</div>
@csrf
<div class="form-group">
    <div class="col-sm-7 col-md-offset-5">
        {!! Form::submit(isset($account)?'Update':'Submit', ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('admin.merchant_payment_accounts.index') !!}" class="btn btn-default">{{__('admin_pages.page_locations_fields_button_cancel')}}</a>
    </div>
</div>
