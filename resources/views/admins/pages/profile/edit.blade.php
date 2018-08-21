@extends('admins.layouts.master')

@section('title')
    Edit profile
@endsection

@section('content-body')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <div class="box box-primary">
                    <form class="form-horizontal" role="form" method="post"
                          action="{{route('admin.profile.update')}}" accept-charset="UTF-8" style="padding: 10px">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="box-body box-profile">
                            <img class="profile-user-img img-responsive img-circle" src="{{asset('adminlte/dist/img/boxed-bg.png')}}" alt="User profile picture">

                            <h3 class="profile-username text-center">{{$admin->firstname.' '.$admin->lastname}}</h3>

                            <p class="text-muted text-center">Administrator account</p>

                            <ul class="list-group list-group-unbordered">

                                <div class="form-group list-group-item">
                                    <label class="control-label col-sm-5" > First name</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" name="firstname" value="{{$admin->firstname}}">
                                    </div>
                                </div>
                                <div class="form-group list-group-item">
                                    <label class="control-label col-sm-5" > Last name</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" name="lastname" value="{{$admin->lastname}}">
                                    </div>
                                </div>
                                <div class="form-group list-group-item">
                                    <label class="control-label col-sm-5" > Phone number</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" name="phonenumber" value="{{$admin->phonenumber}}">
                                    </div>
                                </div>
                                <div class="form-group list-group-item">
                                    <label class="control-label col-sm-5" > Email</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" name="email" value="{{$admin->email}}" disabled>
                                    </div>
                                </div>

                            </ul>
                            <button type="submit" class="btn btn-primary btn-block"><b>Update profile</b></button>
                        </div>
                    </form>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-sm-4"></div>
        </div>
    </section>
@endsection