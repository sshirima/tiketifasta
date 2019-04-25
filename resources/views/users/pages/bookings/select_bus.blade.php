@extends('users.layouts.master_v2')

@section('title')
    @lang('Select bus')
@endsection

@section('content')

    <section class="showcase ">

        <div class="container-fluid showcase-text">
            @include('flash::message')
            @include('includes.errors.message')
            @include('users.pages.bookings.progress_bar')
            <div class="row" id="modify_selection_form" style="display: none;">
                <div class="col-md-12" >
                    <form class="form-horizontal" role="form" method="get"
                          action="{{route('booking.buses.search')}}"
                          accept-charset="UTF-8"
                          {{--style="padding: 20px; opacity: 0.8; background-color: white; border-radius: 10px"--}}>
                        <div class="form-row" style="background-color: white;">

                            <div class="form-group float-label-control col-md-3">
                                <input value="{{!isset($input['from'])?'':$input['from']}}" type="text" class="typeahead form-control form-control-sm" id="from"
                                       placeholder="{{__('From')}}:" name="from">
                            </div>
                            <div class="form-group float-label-control col-md-3">
                                <input value="{{!isset($input['to'])?'':$input['to']}}" type="text" class="typeahead  form-control  form-control-sm" id="to" placeholder="@lang('To'):"
                                       name="to">
                            </div>
                            <div class="form-group float-label-control col-md-3">
                                <input value="{{$input['date']}}" type="text" class="form-control datepicker  form-control-sm" placeholder="{{__('Date')}}:"
                                       id="datepicker" name="date">
                            </div>
                            <div class="col-12 col-md-3">
                                <button style="border-radius: 2px;" type="submit" class="btn btn-block btn-info btn-sm"><span
                                            style="color: white;">@lang('Search')</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <div  class="form-horizontal">
                                <div class="form-row">
                                    <div class="form-group col-md-9">
                                        @lang('Search results for booking') <b>@lang('from'):</b>
                                        <i>{{!isset($input['from'])?__('Anywhere'):$input['from']}}</i> <b>@lang('to'):</b>
                                        <i>{{!isset($input['to'])?__('Anywhere'):$input['to']}}</i> <b>@lang('tarehe')
                                            :</b> {{$input['date']}}
                                        {{--@if(isset($input['from'])&&$input['to'])
                                            <i>{{$input['from']==''?__('Anywhere'):$input['from']}}</i> <b>@lang('to'):</b>
                                            <i>{{$input['to']==''?__('Anywhere'):$input['to']}}</i> <b>@lang('tarehe')
                                                :</b> {{$input['date']}}
                                        @else
                                            <i>{{__('Anywhere')}}</i> <b>@lang('to'):</b>
                                            <i>{{__('Anywhere')}}</i> <b>@lang('tarehe'):</b> {{$input['date']}}
                                        @endif--}}
                                    </div>
                                    <div class="form-group col-md-3">
                                        <button id="modify_selection_button" class="btn btn-default btn-sm pull-right">@lang('Modify selection')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-list table-responsive">
                                @if(isset($trips))
                                    @if(count($trips) > 0 )
                                        <table class="table table-hover">
                                            <thead>
                                            <tr class="text-center">

                                                <th>@lang('Date')</th>
                                                <th>@lang('Company')</th>

                                                <th>@lang('From')</th>
                                                <th>@lang('To')</th>
                                                <th>@lang('Price')</th>
                                                <th>@lang('Departure')</th>
                                                <th>@lang('Arrival')</th>
                                                <th>@lang('Remaining seats')</th>
                                                <th>@lang('Picture')</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($trips as $trip)
                                                <tr class="text-center">
                                                    <td>{{date("d-m-Y", strtotime($trip->date))}}</td>
                                                    <td>{{$trip->bus->merchant->name}}<br>{{$trip->bus->reg_number}}
                                                    </td>
                                                    <td>{{$trip->from}}</td>
                                                    <td>{{$trip->to}}</td>
                                                    <td>{{$trip->price}}</td>
                                                    <td>{{$trip->depart_time}}</td>
                                                    <td>{{$trip->arrival_time}}</td>
                                                    <td>{{($trip->bus->seat_counts - $trip->booked_seats)}} seats</td>
                                                    <td>
                                                        {{--<div id="auto">
                                                            <div><img style="width:80px;height: 50px" src="{{asset('images/buses/T547DFG_1.png')}}" alt="..." /></div>
                                                        </div>--}}

                                                        <a href="#">
                                                            <img type="button"
                                                                 data-toggle="modal"
                                                                 data-id="{{ $trip->date }}"
                                                                 data-title="{{$trip->bus->merchant->name.' - '. $trip->bus->reg_number }}"
                                                                 data-images="{{$trip->bus->image_urls}}"
                                                                 {{--data-images = "{{'["'.asset('images/buses/T547DFG_1.png').'","'.asset('images/buses/T547DFG_1.png').'"]'}}"--}}
                                                                 data-target="#busPicturesModal"
                                                                 style="width:50px;height: 30px"
                                                                 src="{{count($trip->bus->images)>0?asset('images/buses').'/'.$trip->bus->images[0]->image_name:asset('images/buses/').'/no_picture.png'}}"
                                                                 alt="...">
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <form role="form" method="get"
                                                              action="{{route('booking.buses.select', [$trip->bus->id,$trip->schedule_id,$trip->trip_id])}}"
                                                              accept-charset="UTF-8">
                                                            @csrf
                                                            <input type="hidden" name="date" value="{{$trip->date}}">
                                                            <button class="btn btn-info btn-sm" type="submit"><span
                                                                        style="color:white">@lang('Select')</span>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        {!! $trips->appends(\Illuminate\Support\Facades\Input::except('page'))->render() !!}
                                    @else
                                        <div class="alert alert-warning"> No routes has being found</div>
                                    @endif
                                @else
                                    @if(isset($date_error))
                                        <div class="alert alert-danger"> Error: Date submitted was invalid, date can not
                                            be
                                            less
                                            than today
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="busPicturesModal"
         tabindex="-1" role="dialog"
         aria-labelledby="busPicturesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"
                        id="busPicturesModalLabel">Pictures</h4>
                </div>
                <div class="modal-body">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner" id="imageViewer">
                            {{-- <div class="carousel-item active">
                                 <img style="width:500px;height: 300px" src="{{asset('images/buses/T547DFG_1.png')}}" alt="...">
                             </div>
                             <div class="carousel-item">
                                 <img style="width:500px;height: 300px" src="{{asset('images/buses/T547DFG_1.png')}}" alt="...">
                             </div>
                             <div class="carousel-item">
                                 <img style="width:500px;height: 300px" src="{{asset('images/buses/T547DFG_1.png')}}" alt="...">
                             </div>--}}
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                           data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                           data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-default"
                            data-dismiss="modal">Close
                    </button>
                    {{--<span class="pull-right">
          <button type="button" class="btn btn-primary">
            Add to Favorites
          </button>
        </span>--}}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('import_css')
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/user_booking.css')}}">
@endsection

@section('import_js')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

    <script src="{{ URL::asset('js/users/home.js') }}"></script>
    <script src="{{ URL::asset('adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(function () {
            $('#busPicturesModal').on("show.bs.modal", function (e) {
                $("#busPicturesModalLabel").html($(e.relatedTarget).data('title'));
                var images = $(e.relatedTarget).data('images');
                console.log(images);
                $('#imageViewer').html('');
                images.forEach(function (item, index) {
                    if (index === 0) {
                        $('#imageViewer').append('<div class="carousel-item active">\n' +
                            '<img style="width:500px;height: 300px" src="' + item + '" alt="...">\n' +
                            '</div>');
                    } else {
                        $('#imageViewer').append('<div class="carousel-item">\n' +
                            '<img style="width:500px;height: 300px" src="' + item + '" alt="...">\n' +
                            '</div>');
                    }
                });
            });
        });
        $('#modify_selection_button').on('click', function(){

            if($('#modify_selection_form').is(':visible')){

                $('#modify_selection_form').hide();
            }else{
                $('#modify_selection_form').show();
            }

        })
    </script>

    <script type="text/javascript">
        var path = "{{ route('auto_complete_query') }}";
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            startDate: '-3d'
        });
        $('input.typeahead').typeahead({
            source: function (query, process) {
                return $.get(path, {query: query}, function (data) {
                    return process(data);
                });
            }
        });

        (function ($) {
            $.fn.floatLabels = function (options) {

                // Settings
                var self = this;
                var settings = $.extend({}, options);


                // Event Handlers
                function registerEventHandlers() {
                    self.on('input keyup change', 'input, textarea', function () {
                        actions.swapLabels(this);
                    });
                }


                // Actions
                var actions = {
                    initialize: function () {
                        self.each(function () {
                            var $this = $(this);
                            var $label = $this.children('label');
                            var $field = $this.find('input,textarea').first();

                            if ($this.children().first().is('label')) {
                                $this.children().first().remove();
                                $this.append($label);
                            }

                            var placeholderText = ($field.attr('placeholder') && $field.attr('placeholder') != $label.text()) ? $field.attr('placeholder') : $label.text();

                            $label.data('placeholder-text', placeholderText);
                            $label.data('original-text', $label.text());

                            if ($field.val() == '') {
                                $field.addClass('empty')
                            }
                        });
                    },
                    swapLabels: function (field) {
                        var $field = $(field);
                        var $label = $(field).siblings('label').first();
                        var isEmpty = Boolean($field.val());

                        if (isEmpty) {
                            $field.removeClass('empty');
                            $label.text($label.data('original-text'));
                        }
                        else {
                            $field.addClass('empty');
                            $label.text($label.data('placeholder-text'));
                        }
                    }
                }


                // Initialization
                function init() {
                    registerEventHandlers();

                    actions.initialize();
                    self.each(function () {
                        actions.swapLabels($(this).find('input,textarea').first());
                    });
                }

                init();


                return this;
            };

            $(function () {
                $('.float-label-control').floatLabels();
            });
        })(jQuery);
    </script>
@endsection