@extends('admins.layouts.master')

@section('title')
    Delete account
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            Merchant account deletion confirmation
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.merchant_payment_accounts.index')}}"> MPA</a>
            </li>
            <li class="active">Delete</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="box-body">
            <div class="nav-tabs-custom">
                <div class="nav nav-tabs">
                    @include('admins.pages.payment_accounts.merchant_payment_account_options_filed')
                </div>
                <div class="tab-content">
                    @if(isset($account))
                        <form class="form-horizontal" role="form" method="post" action="{{route('admin.merchant_payment_accounts.destroy', $account->id)}}" accept-charset="UTF-8" style="padding: 20px">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <div class="form-group">
                                <div class="col-sm-7 col-md-offset-1">
                                    <div class="form-group">
                                        You are about to delete merchant payment account, are you sure you want to delete.<br>
                                        Note. Once the records are deleted they can not be recovered, proceed with deletion?
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-7 col-md-offset-5">
                                            {!! Form::submit('Confirm deletion', ['class' => 'btn btn-danger']) !!}
                                            <a href="{!! route('admin.merchant_payment_accounts.index') !!}" class="btn btn-default">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @else
                        <div class="form-group">
                            <div class="col-sm-7 col-md-offset-1">
                                <div class="form-group">
                                    Account not found<br>
                                    <a href="{!! route('admin.merchant_payment_accounts.index') !!}" class="btn btn-default">Cancel</a>
                                </div>

                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    {{--<section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-header ">
                Accounts
            </div>
            <div class="box-body">
                {!! $accountTable->render() !!}
            </div>
        </div>
    </section>--}}
@endsection