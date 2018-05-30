@extends('users.layouts.auth_master_v2')

@section('title')
    {{ __('page_auth_register.page_title') }}
@endsection

@section('content')
    <section class="features-icons">
        <div class="row">
            <div  class="col-md-4"></div>
            <div class="col-md-4">
                <h1 class="text-center login-title">{{__('page_auth_register.form_title')}}</h1>
                <div style="padding-right: 10px" class="login-form">
                    <img class="profile-img"
                         src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
                         alt="">

                    <form class="form-signin" method="post" action="{{route('register')}}">
                        <br>
                        <input type="text" name="firstname" id="firstname" placeholder="{{__('page_auth_register.placeholder_first_name')}}"
                               class="form-control" autofocus value="{{ old('firstname') }}">
                        <br>
                        <input type="text" name="lastname" id="lastname" placeholder="{{__('page_auth_register.placeholder_last_name')}}"
                               class="form-control" value="{{ old('lastname') }}">
                        <br>
                        <input type="email" name="email" id="email" placeholder="{{__('page_auth_register.placeholder_email')}}"
                               class="form-control" value="{{ old('email') }}"><br>
                        <input type="password" name="password" id="password" placeholder="{{__('page_auth_register.placeholder_password')}}"
                               class="form-control"><br>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               placeholder="{{__('page_auth_register.placeholder_confirm_password')}}" class="form-control"><br>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="checkbox">
                                    <label>
                                        <input name="agree_terms" type="checkbox">
                                        <a href="#">{{__('page_auth_register.label_terms_conditions')}}</a>
                                    </label>
                                </div>
                            </div>
                        </div> <!-- /.form-group -->
                        <button type="submit" class="btn btn-success btn-block">{{__('page_auth_register.label_register')}}</button>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    </form>
                </div>
            </div>
        </div>
    </section>
    <br>
@endsection

