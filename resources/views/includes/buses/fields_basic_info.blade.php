<div class="form-group">
    {!! Form::label('reg_number', __('admin_pages.page_bus_create_fields_label_reg_number'), ['class'=>'col-sm-5 control-label', 'for'=>'reg_number']) !!}
    <div class="col-sm-7">
        {!! Form::text('reg_number', isset($bus)?$bus[\App\Models\Bus::COLUMN_REG_NUMBER]:old('reg_number'), ['class' => 'form-control', isset($bus)?'disabled':'','placeholder'=>__('admin_pages.page_bus_create_fields_input_placeholder')]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('bustype_id', __('admin_pages.page_bus_create_fields_label_bustype'), ['class'=>'col-sm-5 control-label', 'for'=>'bustype_id']) !!}
    <div class="col-sm-7">
        {{Form::select('bustype_id',$busTypes,isset($bus)?$bus[\App\Models\Bus::COLUMN_BUSTYPE_ID]:null,['class'=>'form-control select2',isset($bus)?'disabled':''])}}
    </div>
</div>

<div class="form-group">
    {!! Form::label('merchant_id', __('admin_pages.page_bus_create_fields_label_merchant'), ['class'=>'col-sm-5 control-label', 'for'=>'merchant_id']) !!}
    <div class="col-sm-7">
        {{Form::select('merchant_id',$merchants,isset($bus)?$bus[\App\Models\Bus::COLUMN_MERCHANT_ID]:null,['class'=>'form-control select2',isset($bus)?'disabled':''])}}
    </div>
</div>

<div class="form-group">
    {!! Form::label('operation_start', __('merchant_pages.page_bus_edit_field_label_operation_period'), ['class'=>'col-sm-5 control-label', 'for'=>'operation_start']) !!}
    <div class="col-sm-7">
        <div class="form-group">
            <div class="col-sm-10">
                <div class="form-group">
                    <div class="control-label col-sm-4">{{__('merchant_pages.page_bus_edit_field_label_from')}}</div>
                    <div class="col-sm-8">
                        <input class="form-control" value="{{isset($bus)?$bus[\App\Models\Bus::COLUMN_OPERATION_START]:''}}" type="date" id="operation_start" name="operation_start" {{Auth::guard('merchant')->check()?'disabled':''}}>
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-label col-sm-4">{{__('merchant_pages.page_bus_edit_field_label_to')}}</div>
                    <div class="col-sm-8">
                        <input class="form-control" value="{{isset($bus)?$bus[\App\Models\Bus::COLUMN_OPERATION_END]:''}}" type="date" id="operation_end" name="operation_end" {{Auth::guard('merchant')->check()?'disabled':''}}>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>