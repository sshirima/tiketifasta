@extends('users.layouts.master')

@section('title')
    {{ __('user_pages.page_title_select_bus') }}
@endsection

@section('contents')
        <div class="center-footer"><h2 class="dark-text">{{__('user_pages.page_select_bus_form_title')}}</h2>
            <div class="colored-line"></div>
            <div class="sub-heading">

            </div>
            <div class="panel panel-success panel-table">
                <div class="panel-heading">
                    <h3 class="panel-title" style="padding: 5px">
                        Available buses from: <strong>{{$journey['source']->name}}</strong> to: <strong>{{$journey['destination']->name}}</strong> on <strong>{{$journey['date']}}</strong>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="content">
                        @include('flash::message')
                        @include('includes.errors.message')
                        <div class="panel-body">
                            {!! $table->render() !!}
                        </div>
                    </div>

                </div>
            </div>

        </div>
@endsection