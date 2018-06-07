<div class="form-group">
    {!! Form::label('driver_name', __('merchant_page_buses.form_field_label_driver_name'), ['class'=>'col-sm-5 control-label', 'for'=>'driver_name']) !!}
    <div class="col-sm-7">
        <h4 ><span class="label label-default">{{$bus[\App\Models\Bus::COLUMN_DRIVER_NAME]}}</span></h4>
    </div>
</div>
<div class="form-group">
    {!! Form::label('conductor_name', __('merchant_page_buses.form_field_label_conductor_name'), ['class'=>'col-sm-5 control-label', 'for'=>'conductor_name']) !!}
    <div class="col-sm-7">
        <h4 ><span class="label label-default">{{$bus[\App\Models\Bus::COLUMN_CONDUCTOR_NAME]}}</span></h4>
    </div>
</div>
<div class="form-group">
    {!! Form::label('condition', __('merchant_page_buses.form_field_label_bus_condition'), ['class'=>'col-sm-5 control-label', 'for'=>'condition']) !!}
    <div class="col-sm-7">
        <h4 ><span class="label {{$bus->condition == \App\Models\Bus::CONDITION_DEFAULT_OPERATIONAL?'label-success':'label-danger'}}">{{$bus[\App\Models\Bus::COLUMN_BUS_CONDITION]}}</span></h4>
    </div>
</div>

<div class="form-group">
    {!! Form::label('state', __('merchant_page_buses.form_field_label_bus_state'), ['class'=>'col-sm-5 control-label', 'for'=>'state']) !!}
    <div class="col-sm-7">
        <h4 ><span class="label {{$bus->state == \App\Models\Bus::STATE_DEFAULT_ENABLED?'label-success':'label-danger'}}">{{$bus[\App\Models\Bus::COLUMN_STATE]}}</span></h4>
    </div>
</div>