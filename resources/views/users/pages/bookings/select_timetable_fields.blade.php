<div class="form-group">
    {!! Form::label('trip_type', 'Select trip type: ', ['class'=>'col-sm-5 control-label', 'for'=>'trip_type']) !!}
    <div class="col-sm-7">
        {!! Form::select('trip_type',['one_way'=>'One way'/*,'round_trip'=>'Round trip'*/] ,null, ['class' => 'form-control',]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('traveling_dates', 'Traveling date: ', ['class'=>'col-sm-5 control-label', 'for'=>'departing_date']) !!}
    <div class="col-sm-7">
        <div class="form-group">
            <div class="col-sm-4 control-label" >Depart:</div>
            <div class="col-sm-8">
                {!! Form::date('departing_date',null, ['class' => 'form-control',]) !!}
            </div>
        </div>
        {{--<div class="form-group">
            <div class="col-sm-4 control-label" >Returning:</div>
            <div class="col-sm-8">
                {!! Form::date('return_date',null, ['class' => 'form-control',]) !!}
            </div>
        </div>--}}
    </div>
</div>

<div class="form-group">
    {!! Form::label('traveling_locations', 'Locations: ', ['class'=>'col-sm-5 control-label', 'for'=>'departing_date']) !!}
    <div class="col-sm-7">
        <div class="form-group">
            <div class="col-sm-4 control-label" >From:</div>
            <div class="col-sm-8">
                {!! Form::select('start_location',$sources,null, ['class' => 'form-control',]) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-4 control-label" >To:</div>
            <div class="col-sm-8">
                {!! Form::select('destination',$destinations,null, ['class' => 'form-control',]) !!}
            </div>
        </div>
    </div>
</div>
@csrf
<div class="form-group">
    <div class="col-sm-7 col-md-offset-5">
        {!! Form::submit('Check availability', ['class' => 'btn btn-primary']) !!}
    </div>
</div>