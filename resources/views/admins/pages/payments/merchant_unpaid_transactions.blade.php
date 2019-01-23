@extends('admins.layouts.master')

@section('title')
    Unpaid transactions
@endsection

@section('content-head')
    <section class="content-header">
        <h1>
            Unpaid transactions
            <small>These are transaction which are still not paid to the bus merchants, this can be due to failed payment schedule</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('admin.merchants_transactions.unpaid')}}"> Unpaid transactions</a>
            </li>
            <li class="active">View</li>
        </ol>
    </section>
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="nav-tabs-custom">
            <div class="nav nav-tabs">
                @include('admins.pages.payments.payments_disbursement_panel')
            </div>
            <div class="tab-content">
                @if(isset($table))
                    {!! $table->render() !!}
                @endif

            </div>
        </div>
    </section>

@endsection