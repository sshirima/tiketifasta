<div class="form-group">
    {!! Form::label('name', __('admin_pages.page_bustype_fields_label_model_name'), ['class'=>'col-sm-3 control-label', 'for'=>'name']) !!}
    <div class="col-sm-5">
        {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder'=>__('admin_pages.page_bustype_fields_input_model_name_placeholder')]) !!}
    </div>

</div>
<div class="form-group">
    {!! Form::label('seats', __('admin_pages.page_bustype_fields_label_number_of_seats'), ['class'=>'col-sm-3 control-label', 'for'=>'seats']) !!}
    <div class="col-sm-5">
        {!! Form::number('seats', old('seats'), ['class' => 'form-control', 'placeholder'=>__('admin_pages.page_bustype_fields_input_number_of_seats_placeholder')]) !!}
    </div>

</div>
<div class="form-group">
    {!! Form::label('select_arrangement', __('admin_pages.page_bustype_fields_label_seat_arrangement'), ['class'=>'col-sm-3 control-label', 'for'=>'select_arrangement']) !!}
    <div class="col-sm-3">
        {!! Form::select('select_arrangement', [0=>__('admin_pages.page_bustype_fields_select_default'),'1x1'=>'1x1','1x2'=>'1x2','2x1'=>'2x1','2x2'=>'2x2','2x3'=>'2x3','3x2'=>'3x2','3x3'=>'3x3'], null,['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group bus-layout" >
    <div class="col-sm-2 col-sm-offset-1 btn btn-default" id="btn-check-layout">{{__('admin_pages.page_bustype_fields_button_display_layout')}}</div>
    <div class="col-sm-9">
        <div class=" bus-seats col-sm-7">
            <div ><div id="seat-map"></div></div>
        </div>
        <div class="col-sm-5 seat-legend">
            <textarea class="form-control" rows="10" type="text" id="seat_arrangement" name="seat_arrangement"></textarea>
            <div id="legend"></div>
        </div>
    </div>

</div>
@csrf
<div class="form-group">
    <div class="col-sm-7 col-md-offset-5">
        {!! Form::submit(__('admin_pages.page_bustype_fields_button_save'), ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('admin.bustype.index') !!}" class="btn btn-default">{{__('admin_pages.page_bustype_fields_button_cancel')}}</a>
    </div>
</div>
