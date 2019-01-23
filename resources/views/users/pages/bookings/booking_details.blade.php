@extends('users.layouts.master_v2')

@section('title')
    {{ __('user_pages.page_title_booking_details') }}
@endsection


@section('content')
    <section class="showcase">

        <div class="container-fluid p-0">

            <div class="row no-gutters">
                <div class="col-lg-7 order-lg-1 my-auto showcase-text">
                    <div class="text-center">
                        @include('users.pages.bookings.booking_details_fields')
                    </div>
                </div>
                <div class="col-lg-5 order-lg-1 my-auto showcase-text">

                        <h3 class="mb-5">Journey details</h3>
                        <fieldset class="border p-2">
                            <legend  class="w-auto">{{$trip->from}} to {{$trip->to}}</legend>
                            <div class="row form-group">
                                <div  class="control-label col-sm-4 pull-right"><strong class="pull-right">Date:</strong></div>
                                <div class="col-sm-8">
                                    {{$trip->date}}
                                </div>
                            </div>

                            <div class="row  form-group">
                                <div  class="control-label col-sm-4 pull-right"><strong class="pull-right">Departing:</strong></div>
                                <div class="col-sm-8">
                                    {{$trip->depart_time}}
                                </div>
                            </div>
                            <div class="row  form-group">
                                <div  class="control-label col-sm-4 pull-right"><strong class="pull-right">Arriving:</strong></div>
                                <div class="col-sm-8">
                                    {{$trip->arrival_time}}
                                </div>
                            </div>
                            <div class="row  form-group">
                                <div  class="control-label col-sm-4 pull-right"><strong class="pull-right">Reg #:</strong></div>
                                <div class="col-sm-8">
                                    {{$trip->bus->reg_number}}
                                </div>
                            </div>
                            <div class="row  form-group">
                                <div  class="control-label col-sm-4 pull-right"><strong class="pull-right">Company:</strong></div>
                                <div class="col-sm-8">
                                    {{$trip->bus->merchant->name}}
                                </div>
                            </div>
                            <div class="row  form-group">
                                <div  class="control-label col-sm-4 pull-right"><strong class="pull-right">Seat:</strong></div>
                                <div class="col-sm-8">
                                    {{$trip->bus->seat_name}}
                                </div>
                            </div>
                            <div class="row  form-group">
                                <div  class="control-label col-sm-4 pull-right"><strong class="pull-right">Price:</strong></div>
                                <div class="col-sm-8">
                                    {{$trip->price}} Tsh.
                                </div>
                            </div>

                        </fieldset>
                    {{--<h3 class="mb-5">Journey details</h3>
                    <div>
                        <span>Date: </span><span><strong>{{$trip->date}}</strong></span>
                    </div>
                    <div>
                        <span>Depart time: </span><span><strong>{{$trip->depart_time}}</strong></span>
                    </div>
                    <div>
                        <span>Arrival time: </span><span><strong>{{$trip->arrival_time}}</strong></span>
                    </div>
                    <div>
                        <span>Bus info: </span><span><strong>{{$trip->bus->merchant->name}}</strong>  <strong> {{' ('.$trip->bus->reg_number.') '}} </strong></span>
                    </div>
                    <div>
                    <div>
                        <span>Seat name: </span><span><strong>{{$trip->bus->seat_name}}</strong></span>
                    </div>
                    <div>
                        <span>Ticket price: </span><span><strong>{{$trip->price}}</strong>  <strong> {{' (Tshs) '}} </strong></span>
                    </div>
                </div>--}}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('import_js')

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

    <script type="text/javascript">
        var path = "{{ route('auto_complete_query') }}";
        $('input.typeahead').typeahead({
            source: function (query, process) {
                return $.get(path, {query: query}, function (data) {
                    return process(data);
                });
            }
        });
    </script>

@endsection