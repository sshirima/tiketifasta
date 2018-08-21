@extends('merchants.layouts.master')

@section('title')
    User profile
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="{{asset('adminlte/dist/img/boxed-bg.png')}}" alt="User profile picture">

                        <h3 class="profile-username text-center">{{$merchant->firstname.' '.$merchant->lastname}}</h3>

                        <p class="text-muted text-center">Merchant administrator</p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>First name</b> <a class="pull-right">{{$merchant->firstname}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Last name</b> <a class="pull-right">{{$merchant->lastname}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Phone number</b> <a class="pull-right">{{$merchant->phonenumber}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Email address</b> <a class="pull-right">{{$merchant->email}}</a>
                            </li>

                        </ul>
                        <a href="{{route('merchant.profile.edit')}}" class="btn btn-primary btn-block"><b>Edit profile</b></a>
                        <a href="{{route('merchant.password.change')}}" class="btn btn-danger btn-block"><b>Change password</b></a>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-sm-4"></div>
        </div>
    </section>
@endsection