<div class="form-group">
    {!! Form::label('bus_class_id', __('admin_pages.page_routes_fields_label_bus_class'), ['class'=>'col-sm-5 control-label', 'for'=>'bus_class_id']) !!}
    <div class="col-sm-6">
        @if(!isset($ticketPrice))
            {{Form::select('bus_class_id',$busClasses,old('bus_class_id'),['class'=>'form-control'])}}
        @else
            {{Form::text('bus_class_id',$ticketPrice->busClass->class_name,['class'=>'form-control','disabled'=>'true'])}}
        @endif

    </div>
</div>

<div class="form-group">
    {!! Form::label('start_location', __('admin_pages.page_routes_fields_label_from'), ['class'=>'col-sm-5 control-label', 'for'=>'start_location']) !!}
    <div class="col-sm-6">
        @if(!isset($ticketPrice))
            {{Form::select('start_location',$locations,old('start_location'),['class'=>'form-control'])}}
        @else
            {{Form::text('start_location',$ticketPrice->startLocation->name,['class'=>'form-control','disabled'=>'true'])}}
        @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('last_location', __('admin_pages.page_routes_fields_label_to'), ['class'=>'col-sm-5 control-label', 'for'=>'last_location']) !!}
    <div class="col-sm-6">
        @if(!isset($ticketPrice))
            {{Form::select('last_location',$locations,old('last_location'),['class'=>'form-control'])}}
        @else
            {{Form::text('last_location',$ticketPrice->lastLocation->name,['class'=>'form-control','disabled'=>'true'])}}
        @endif

    </div>
</div>

<div class="form-group">
    {!! Form::label('price', __('admin_pages.page_routes_fields_label_price'), ['class'=>'col-sm-5 control-label', 'for'=>'price']) !!}
    <div class="col-sm-6">
        @if(!isset($ticketPrice))
            {{Form::text('price',null,['class'=>'form-control'])}}
        @else
            {{Form::text('price',$ticketPrice->price,['class'=>'form-control'])}}
        @endif

    </div>
</div>


@csrf
<div class="form-group">
    <div class="col-sm-7 col-md-offset-5">
        {!! Form::submit(__('admin_pages.page_ticket_price_fields_button_submit'), ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('admin.ticket_prices.index') !!}" class="btn btn-default">{{__('admin_pages.page_routes_fields_button_cancel')}}</a>
    </div>
</div>
