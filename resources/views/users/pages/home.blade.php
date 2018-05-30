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
                    <form>
                        <div class="form-row">
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <input type="email" class="form-control form-control-lg" placeholder="From:">
                            </div>
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <input type="email" class="form-control form-control-lg" placeholder="To:">
                            </div>
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <input type="email" class="form-control form-control-lg" placeholder="Date:">
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