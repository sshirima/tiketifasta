<div class="form-group">
    {!! Form::label('driver_name', __('merchant_page_buses.form_field_label_driver_name'), ['class'=>'col-sm-5 control-label', 'for'=>'driver_name']) !!}
    <div class="col-sm-7">
        {{Form::text('driver_name',isset($bus)?$bus[\App\Models\Bus::COLUMN_DRIVER_NAME]:'',['class'=>'form-control'])}}
    </div>
</div>
<div class="form-group">
    {!! Form::label('conductor_name', __('merchant_page_buses.form_field_label_conductor_name'), ['class'=>'col-sm-5 control-label', 'for'=>'conductor_name']) !!}
    <div class="col-sm-7">
        {{Form::text('conductor_name',isset($bus)?$bus[\App\Models\Bus::COLUMN_CONDUCTOR_NAME]:'',['class'=>'form-control'])}}
    </div>
</div>
<div class="form-group">
    {!! Form::label('condition', __('merchant_page_buses.form_field_label_bus_condition'), ['class'=>'col-sm-5 control-label', 'for'=>'condition']) !!}
    <div class="col-sm-7">
        {{Form::select('condition',$conditions,isset($bus)?$bus[\App\Models\Bus::COLUMN_BUS_CONDITION]:'',['class'=>'form-control'])}}
    </div>
</div>