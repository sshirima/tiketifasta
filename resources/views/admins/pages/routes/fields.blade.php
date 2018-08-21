<div class="form-group">
    {!! Form::label('route_name', __('admin_pages.page_routes_fields_label_route_name'), ['class'=>'col-sm-5 control-label', 'for'=>'route_name']) !!}
    <div class="col-sm-6">
        {!! Form::text(\App\Models\Route::COLUMN_ROUTE_NAME, old('route_name'), ['class' => 'form-control', 'placeholder'=>__('admin_pages.page_routes_fields_input_route_name_placeholder')]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('travelling_days', __('admin_pages.page_routes_fields_label_travelling_days'), ['class'=>'col-sm-5 control-label', 'for'=>'start_location']) !!}
    <div class="col-sm-6">
        {{Form::select(\App\Models\Route::COLUMN_TRAVELLING_DAYS,$travellingDays,old(\App\Models\Route::COLUMN_TRAVELLING_DAYS),['class'=>'form-control'])}}
    </div>
</div>

<div class="form-group">
    {!! Form::label('start_location', __('admin_pages.page_routes_fields_label_start_location'), ['class'=>'col-sm-5 control-label', 'for'=>'start_location']) !!}
    <div class="col-sm-6">
        {{Form::select(\App\Models\Route::COLUMN_START_LOCATION,$locations,old(\App\Models\Route::COLUMN_START_LOCATION),['class'=>'form-control   select2'])}}
    </div>
</div>

<div class="destinations">
    <div>
        <div class="entry form-group">
            {!! Form::label('destinations', __('admin_pages.page_routes_fields_label_destination'), ['class'=>'col-sm-5 control-label', 'for'=>'destinations']) !!}
            <div class="col-sm-6">
                {{Form::select('destinations[]',$locations,null,['class'=>'form-control   select2'])}}
            </div>
            <div class="col-sm-1">
                <span class="input-group-btn ">
                    <span class="btn btn-success btn-add" type="button"><span class="glyphicon glyphicon-plus"></span></span>
                </span>
            </div>
        </div>
    </div>
</div>

@csrf
<div class="form-group">
    <div class="col-sm-7 col-md-offset-5">
        {!! Form::submit(__('admin_pages.page_routes_fields_button_submit'), ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('admin.routes.index') !!}" class="btn btn-default">{{__('admin_pages.page_routes_fields_button_cancel')}}</a>
    </div>
</div>
