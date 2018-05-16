<div class="form-group">
    {!! Form::label('ticket_type', __('merchant_pages.page_ticketprice_create_field_label_ticket_type'), ['class'=>'col-sm-3 control-label', 'for'=>'ticket_type']) !!}

    <div class="col-sm-5">
        {{Form::select('ticket_type',['Adult'=>__('merchant_pages.page_ticketprice_create_field_select_option_adult'),
        'Child'=>__('merchant_pages.page_ticketprice_create_field_select_option_child')],null,['class'=>'form-control'])}}
    </div>
</div>

<div class="form-group">
    {!! Form::label('source', __('merchant_pages.page_ticketprice_create_field_label_source'), ['class'=>'col-sm-3 control-label', 'for'=>'source']) !!}

    <div class="col-sm-5">
        {{Form::text('source',$subRoute->source()->first()->name,['class'=>'form-control', 'disabled'=>'true'])}}
        </div>
</div>

<div class="form-group">
    {!! Form::label('destination', __('merchant_pages.page_ticketprice_create_field_label_destination'), ['class'=>'col-sm-3 control-label', 'for'=>'destination']) !!}

    <div class="col-sm-5">
        {{Form::text('destination',$subRoute->destination()->first()->name,['class'=>'form-control', 'disabled'=>'true'])}}
    </div>
</div>

<div class="form-group">
    {!! Form::label('price', __('merchant_pages.page_ticketprice_create_field_label_price'), ['class'=>'col-sm-3 control-label', 'for'=>'price']) !!}

    <div class="col-sm-5">
        {{Form::text('price',$subRoute->ticketPrice->price,['class'=>'form-control'])}}
    </div>
</div>
@csrf
<div class="form-group">
    <div class="col-sm-7 col-md-offset-5">
        {!! Form::submit(__('merchant_pages.page_ticketprice_create_field_button_create'), ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('merchant.ticket_price.index',$bus->id) !!}" class="btn btn-default">{{__('merchant_pages.page_ticketprice_create_field_button_cancel')}}</a>
    </div>
</div>