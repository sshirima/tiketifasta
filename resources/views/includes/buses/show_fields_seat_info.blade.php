<div class="col-sm-4">
    <div id="seat_map">
        <div class="front-indicator"><h3 ><span class="label label-info">Bus front</span></h3></div><br>
    </div>
</div>
<div class="col-sm-4">
    <div class="form-group">
        {!! Form::label('state', __('merchant_page_buses.form_field_label_class'), ['class'=>'col-sm-5 control-label', 'for'=>'state']) !!}
        <div class="col-sm-7">
            <h4 ><span class="label label-default">{{$bus[\App\Models\Bus::COLUMN_CLASS]}}</span></h4>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('state', __('merchant_page_buses.form_field_label_model'), ['class'=>'col-sm-5 control-label', 'for'=>'state']) !!}
        <div class="col-sm-7">
            <h4 ><span class="label label-default">{{$bus->busType->name}}</span></h4>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('state', __('merchant_page_buses.form_field_label_seats_number'), ['class'=>'col-sm-5 control-label', 'for'=>'state']) !!}
        <div class="col-sm-7">
            <h4 ><span class="label label-default">{{$bus->busType->seats}}</span></h4>
        </div>
    </div>
</div>