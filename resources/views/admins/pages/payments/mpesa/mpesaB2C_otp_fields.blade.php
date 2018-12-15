<div class="form-group">
    {!! Form::label('otp', 'OTP:', ['class'=>'col-sm-5 control-label', 'for'=>'otp']) !!}
    <div class="col-sm-7">
        {!! Form::number('otp', null, ['class' => 'form-control', 'placeholder'=>'OTP']) !!}
    </div>
</div>
@csrf
<div class="form-group">
    <div class="col-sm-7 col-md-offset-5">
        {!! Form::submit(__('admin_pages.page_payment_account_fields_verify'), ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('admin.mpesab2c.send_cash') !!}" class="btn btn-default">{{__('admin_pages.page_locations_fields_button_cancel')}}</a>
    </div>
</div>