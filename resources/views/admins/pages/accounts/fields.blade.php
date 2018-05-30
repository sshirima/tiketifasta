<div class="form-group">
    {!! Form::label('name', __('admin_pages.page_locations_fields_label_name'), ['class'=>'col-sm-5 control-label', 'for'=>'name']) !!}
    <div class="col-sm-7">
        {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder'=>__('admin_pages.page_locations_fields_input_name_placeholder')]) !!}
    </div>

</div>
@csrf
<div class="form-group">
    <div class="col-sm-7 col-md-offset-5">
        {!! Form::submit(__('admin_pages.page_locations_fields_button_save'), ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('admin.location.index') !!}" class="btn btn-default">{{__('admin_pages.page_locations_fields_button_cancel')}}</a>
    </div>
</div>
