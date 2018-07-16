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