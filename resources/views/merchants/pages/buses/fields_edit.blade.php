<div class="form-group">
    {!! Form::label('reg_number', __('merchant_pages.page_bus_edit_field_label_reg_number'), ['class'=>'col-sm-3 control-label', 'for'=>'reg_number']) !!}
    <div class="col-sm-8">
        {!! Form::text('reg_number', $bus->reg_number, ['class' => 'form-control','disabled'=>'true']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('bustype_id', __('merchant_pages.page_bus_edit_field_label_bus_type'), ['class'=>'col-sm-3 control-label', 'for'=>'bustype_id']) !!}
    <div class="col-sm-8">
        {{Form::text('bustype_id',$bus->busType()->first()->name,['class'=>'form-control', 'disabled'=>'true'])}}
    </div>
</div>

<div class="form-group">
    {!! Form::label('route_id', __('merchant_pages.page_bus_edit_field_label_route'), ['class'=>'col-sm-3 control-label', 'for'=>'route_id']) !!}
    <div class="col-sm-8">
        <select class="form-control" id="route_id" name="route_id">
            <option value="">{{__('merchant_pages.page_bus_edit_field_select_route')}}</option>
            @foreach($routes as $route)
                <option value="{{$route->id}}">{{$route->route_name}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="locations">

</div>

<div class="trip_dates">
    <div>
        <div class="entry form-group">
            {!! Form::label('trip_dates', __('merchant_pages.page_bus_edit_field_label_trip_date'), ['class'=>'col-sm-3 control-label', 'for'=>'trip_dates']) !!}
            <div class="col-sm-8">
                <input type="text" name="trip_dates" id="trip_dates" class="form-control date" placeholder="{{__('merchant_pages.page_bus_edit_field_input_trip_date_placeholder')}}">
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('operation_start', __('merchant_pages.page_bus_edit_field_label_operation_period'), ['class'=>'col-sm-3 control-label', 'for'=>'operation_start']) !!}
    <div class="col-sm-8">
        <div class="form-group">
            <div class="col-sm-10">
                <div class="form-group">
                    <div class="control-label col-sm-4">{{__('merchant_pages.page_bus_edit_field_label_from')}}</div>
                    <div class="col-sm-8">
                        <input class="form-control" type="date" value="{{$bus->operation_start}}" id="operation_start" name="operation_start" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-label col-sm-4">{{__('merchant_pages.page_bus_edit_field_label_to')}}</div>
                    <div class="col-sm-8">
                        <input class="form-control" type="date" value="{{$bus->operation_end}}" id="operation_end" name="operation_end" disabled>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@csrf
