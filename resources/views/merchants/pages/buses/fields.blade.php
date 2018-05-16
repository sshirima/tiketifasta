<div class="form-group">
    {!! Form::label('reg_number', 'Reg #:', ['class'=>'col-sm-5 control-label', 'for'=>'reg_number']) !!}
    <div class="col-sm-7">
        {!! Form::text('reg_number', old('reg_number'), ['class' => 'form-control', 'placeholder'=>'Reg number...']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('bustype_id', 'Select bus type:', ['class'=>'col-sm-5 control-label', 'for'=>'bustype_id']) !!}
    <div class="col-sm-7">
        {{Form::select('bustype_id',$bustypes,null,['class'=>'form-control'])}}
    </div>
</div>

<div class="form-group">
    {!! Form::label('route_id', 'Select route:', ['class'=>'col-sm-5 control-label', 'for'=>'route_id']) !!}
    <div class="col-sm-7">
        {{Form::select('route_id',$routes,null,['class'=>'form-control'])}}
    </div>
</div>

<div class="locations">

</div>
<div class="form-group">
    {!! Form::label('operation_start', 'Period operating', ['class'=>'col-sm-5 control-label', 'for'=>'operation_start']) !!}
    <div class="col-sm-7">
        <div class="form-group">
            <div class="col-sm-10">
                <div class="form-group">
                    <div class="control-label col-sm-4">From:</div>
                    <div class="col-sm-8">
                        <input class="form-control" type="date" value="" id="operation_start" name="operation_start">
                    </div>
                </div>
                <div class="form-group">
                    <div class="control-label col-sm-4">To:</div>
                    <div class="col-sm-8">
                        <input class="form-control" type="date" value="" id="operation_end" name="operation_end">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@csrf
<div class="form-group">
    <div class="col-sm-7 col-md-offset-5">
        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('merchant.bus.index') !!}" class="btn btn-default">Cancel</a>
    </div>
</div>
