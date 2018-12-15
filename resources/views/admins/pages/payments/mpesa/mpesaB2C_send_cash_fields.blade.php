<div class="form-group">
    {!! Form::label('operator', __('admin_pages.page_payment_method_fields_label_name'), ['class'=>'col-sm-5 control-label', 'for'=>'operator']) !!}
    <div class="col-sm-7">
        {{Form::select('payment_mode',[0=>'Select payment method','mpesa'=>'M-PESA','tigopesa'=>'TIGO-PESA'],null,['class'=>'form-control'])}}
    </div>
</div>
<div class="form-group">
    {!! Form::label('receiver', 'Receiver', ['class'=>'col-sm-5 control-label', 'for'=>'receiver']) !!}
    <div class="col-sm-7">
        {!! Form::number('receiver', old('receiver'), ['class' => 'form-control', 'placeholder'=>'Receiver']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('amount', 'Amount', ['class'=>'col-sm-5 control-label', 'for'=>'amount']) !!}
    <div class="col-sm-7">
        {!! Form::number('amount', old('amount'), ['class' => 'form-control', 'placeholder'=>'Amount']) !!}
    </div>
</div>
@csrf
<div class="form-group">
    <div class="col-sm-7 col-md-offset-5">
        {!! Form::submit(__('admin_pages.page_payment_account_fields_issue'), ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('admin.tigob2c.send_cash') !!}" class="btn btn-default">{{__('admin_pages.page_locations_fields_button_cancel')}}</a>
    </div>
</div>