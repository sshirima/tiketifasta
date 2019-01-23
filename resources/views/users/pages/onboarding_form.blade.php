@extends('users.layouts.master_v2')

@section('title')
    On boarding
@endsection

@section('content')
    <section class="features-icons bg-light text-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="lead mb-0 alert alert-info">On boarding ticket page, please input your ticket reference
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    @include('includes.errors.message')
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-6">
                    <form role="form" method="GET"
                          action="{{route('merchant.onboarding.ticket_info')}}"
                          accept-charset="UTF-8">
                        <div class="row ">
                            <div class="col-sm-12 text-center">
                                {!! Form::text('ticket_reference',old('ticket_reference'), ['class' => 'form-control', 'required', 'placeholder'=>'Ticket reference']) !!}
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <strong class="col-sm-4"> </strong>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                        <br>
                        @csrf
                    </form>
                </div>
            </div>

        </div>
    </section>

@endsection


