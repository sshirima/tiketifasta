<div class="form-group">
    {!! Form::label('reg_number', __('admin_pages.page_bus_create_fields_label_reg_number'), ['class'=>'col-sm-5 control-label', 'for'=>'reg_number']) !!}
    <div class="col-sm-7">
        <h4 ><span class="label label-default">{{$bus->reg_number}}</span></h4>
    </div>
</div>

<div class="form-group">
    {!! Form::label('bustype_id', __('admin_pages.page_bus_create_fields_label_bustype'), ['class'=>'col-sm-5 control-label', 'for'=>'bustype_id']) !!}
    <div class="col-sm-7">
        <h4 ><span class="label label-default">{{$bus->busType->name}}</span></h4>
    </div>
</div>

<div class="form-group">
    {!! Form::label('merchant_id', __('admin_pages.page_bus_create_fields_label_merchant'), ['class'=>'col-sm-5 control-label', 'for'=>'merchant_id']) !!}
    <div class="col-sm-7">
        <h4 ><span class="label label-default">{{$bus->merchant->name}}</span></h4>
    </div>
</div>

<div class="form-group">
    {!! Form::label('class', __('admin_pages.page_bus_show_fields_label_class'), ['class'=>'col-sm-5 control-label', 'for'=>'class']) !!}
    <div class="col-sm-7">
        <h4 ><span class="label label-default">{{$bus->class}}</span></h4>
    </div>
</div>