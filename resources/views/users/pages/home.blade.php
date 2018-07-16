@extends('users.layouts.master_v2')

@section('title')
Home
@endsection

@section('content')
    <header class="masthead text-white text-center">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <h1 class="mb-5">What to book a ticket?</h1>
                    <h4 class="mb-5">You can search for one now!</h4>
                </div>
                <div class="col-md-12  mx-auto">
                    <form class="form-horizontal" role="form" method="get"
                          action="{{route('booking.buses.search')}}"
                          accept-charset="UTF-8" style="padding: 20px">
                        <div class="form-row">
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <input type="text" class="form-control form-control-lg" placeholder="From:" name="from">
                            </div>
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <input type="text" class="form-control form-control-lg" placeholder="To:" name="to">
                            </div>
                            <div class="col-12 col-md-3 mb-2 mb-md-0 date-field">
                                <input type="text" class="form-control form-control-lg" placeholder="Date:" id="datepicker" name="date">
                            </div>
                            <div class="col-12 col-md-3">
                                <button type="submit" class="btn btn-block btn-lg btn-primary">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </header>
@endsection

@section('import_css')
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
@endsection

@section('import_js')

    <script src="{{ URL::asset('adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

   <script src="{{ URL::asset('js/users/home.js') }}"></script>

@endsection