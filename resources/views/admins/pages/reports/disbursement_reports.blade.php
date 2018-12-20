@extends('admins.layouts.master')

@section('title')
     Disbursement report
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            Total disbursement:
            <small>Disbursement from ---- to -----</small>
        </h1>
        <ol class="breadcrumb">

        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">

        <div class="nav-tabs-custom">

            <div class="nav nav-tabs">
                @include('admins.pages.reports.disbursement_options_filed')
            </div>
            <div class="tab-content">
                <div class="form-horizontal">
                    <div class="form-group">
                        {!! Form::label('start_date', 'From', ['class'=>'col-sm-1 control-label', 'for'=>'start_date']) !!}
                        <div class="col-sm-2">
                            {!! Form::date('start_date', old('start_date'), ['class' => 'form-control']) !!}
                        </div>
                        {!! Form::label('end_date', 'To', ['class'=>'col-sm-1 control-label', 'for'=>'end_date']) !!}
                        <div class="col-sm-2">
                            {!! Form::date('end_date', old('end_date'), ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-3">
                            {{Form::select('payment_mode',['all'=>'All','mpesa'=>'M-PESA','tigopesa'=>'TIGO-PESA'],null,['class'=>'form-control'])}}
                        </div>
                        <div class="col-sm-2">
                            {!! Form::submit('Refresh', ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                </div>
                {!! $table->render() !!}
            </div>
        </div>
    </section>
@endsection