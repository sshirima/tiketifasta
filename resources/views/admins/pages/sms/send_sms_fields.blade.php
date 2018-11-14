<div class="form-group">
    {!! Form::label('operator', __('admin_pages.page_payment_method_fields_label_name'), ['class'=>'col-sm-5 control-label', 'for'=>'operator']) !!}
    <div class="col-sm-7">
        {{Form::select('operator',[0=>'Select payment method','mpesa'=>'M-PESA','tigopesa'=>'TIGO-PESA','airtelmoney'=>'AIRTEL-MONEY'],'tigopesa',['class'=>'form-control'])}}
    </div>
</div>
<div class="form-group">
    {!! Form::label('receiver', 'Receiver', ['class'=>'col-sm-5 control-label', 'for'=>'receiver']) !!}
    <div class="col-sm-7">
        {!! Form::number('receiver', old('receiver'), ['class' => 'form-control', 'placeholder'=>'Receiver']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('message', 'Message', ['class'=>'col-sm-5 control-label', 'for'=>'message']) !!}
    <div class="col-sm-7">
        {!! Form::text('message', old('message'), ['class' => 'form-control', 'placeholder'=>'Text, maximum 160']) !!}
    </div>
</div>
@csrf
<div class="form-group">
    <div class="col-sm-7 col-md-offset-5">
        {!! Form::submit(__('admin_pages.page_payment_account_fields_send'), ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('admin.sent_sms.index') !!}" class="btn btn-default">{{__('admin_pages.page_locations_fields_button_cancel')}}</a>
    </div>
</div>