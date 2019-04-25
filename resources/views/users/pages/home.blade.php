@extends('users.layouts.master_v2')

@section('title')
TiketiFasta
@endsection

@section('content')
    <header class="masthead text-white text-center">
        <div class="overlay"></div>
        <div class="container">

            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <h1 class="mb-5">@lang('Want to book a ticket?')</h1>
                    <h4 class="mb-5">@lang('You can search, book and pay for one now!')</h4>
                </div>
                <div class="col-md-12  mx-auto">
                    <form class="form-horizontal" role="form" method="get"
                          action="{{route('booking.buses.search')}}"
                          accept-charset="UTF-8" style="padding: 20px; opacity: 0.8; background-color: white; border-radius: 10px">
                        <div class="form-row" style="background-color: white;">

                            <div class="form-group float-label-control col-md-3">
                                <label for="from">{{__('From')}}</label>
                                <input style="padding:10px;" type="text" class="typeahead form-control form-control-lg" id="from" placeholder="{{__('From')}}:" name="from">
                            </div>
                            <div class="form-group float-label-control col-md-3">
                                <label for="from">{{__('To')}}</label>
                                <input type="text" class="typeahead  form-control form-control-lg" id="to" placeholder="@lang('To'):" name="to">
                            </div>
                            <div class="form-group float-label-control col-md-3">
                                <label for="from">{{__('Date')}}</label>
                                <input type="text" class="form-control form-control-lg datepicker" placeholder="{{__('Date')}}:" id="datepicker" name="date">
                            </div>
                            <div class="col-12 col-md-3">
                                <button style="border-radius: 2px;" type="submit" class="btn btn-block btn-lg btn-info"><span style="color: white;">@lang('Search')</span></button>
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
    <link rel="stylesheet" href="{{asset('css/floating_labels_input.css')}}">
@endsection

@section('import_js')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>


    <script src="{{ URL::asset('js/users/home.js') }}"></script>
    <script src="{{ URL::asset('adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <script type="text/javascript">
        var path = "{{ route('auto_complete_query') }}";
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            startDate: '-3d'
        });
        $('input.typeahead').typeahead({
            source:  function (query, process) {
                return $.get(path, { query: query }, function (data) {
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
                    initialize: function() {
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