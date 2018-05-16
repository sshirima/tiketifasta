<div class="form-group">
    {!! Form::label('reg_number', __('admin_pages.page_bus_create_fields_label_reg_number'), ['class'=>'col-sm-5 control-label', 'for'=>'reg_number']) !!}
    <div class="col-sm-7">
        {!! Form::text('reg_number', old('reg_number'), ['class' => 'form-control', 'placeholder'=>__('admin_pages.page_bus_create_fields_input_placeholder')]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('bustype_id', __('admin_pages.page_bus_create_fields_label_bustype'), ['class'=>'col-sm-5 control-label', 'for'=>'bustype_id']) !!}
    <div class="col-sm-7">
        {{Form::select('bustype_id',$busTypes,null,['class'=>'form-control'])}}
    </div>
</div>

<div class="form-group">
    {!! Form::label('merchant_id', __('admin_pages.page_bus_create_fields_label_merchant'), ['class'=>'col-sm-5 control-label', 'for'=>'merchant_id']) !!}
    <div class="col-sm-7">
        {{Form::select('merchant_id',$merchants,null,['class'=>'form-control'])}}
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
                        <input class="form-control" type="date" id="operation_start" name="operation_start">
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-label col-sm-4">{{__('merchant_pages.page_bus_edit_field_label_to')}}</div>
                    <div class="col-sm-8">
                        <input class="form-control" type="date" id="operation_end" name="operation_end">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@csrf
<div class="form-group">
    <div class="col-sm-7 col-md-offset-5">
        {!! Form::submit(__('admin_pages.page_bus_fields_button_save'), ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('admin.buses.index') !!}" class="btn btn-default">{{__('admin_pages.page_bus_fields_button_cancel')}}</a>
    </div>
</div>
