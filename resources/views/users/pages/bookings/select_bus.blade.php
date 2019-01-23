@extends('users.layouts.master_v2')

@section('title')
    Select bus
@endsection

@section('content')

    <section class="showcase">

        <div class="container-fluid p-0">
            <div class="row no-gutters">
                <div class="col-lg-12 my-auto showcase-text text-center">
                    @if(isset($trips))
                        @if(count($trips) > 0 )
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>
                                    </th>
                                    <th>Date</th>
                                    <th>Bus name/Company</th>
                                    <th>Reg number</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Price</th>
                                    <th>Depart at</th>
                                    <th>Arrive at</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($trips as $trip)
                                    <tr>
                                        <td>
                                            {{--<div id="auto">
                                                <div><img style="width:80px;height: 50px" src="{{asset('images/buses/T547DFG_1.png')}}" alt="..." /></div>
                                            </div>--}}

                                            <a href="#">
                                                <img type="button"
                                                     data-toggle="modal"
                                                     data-id="{{ $trip->date }}"
                                                     data-title="{{$trip->bus->merchant->name.' - '. $trip->bus->reg_number }}"
                                                     data-images ="{{$trip->bus->image_urls}}"
                                                     {{--data-images = "{{'["'.asset('images/buses/T547DFG_1.png').'","'.asset('images/buses/T547DFG_1.png').'"]'}}"--}}
                                                     data-target="#busPicturesModal"
                                                     style="width:80px;height: 50px"
                                                     src="{{count($trip->bus->images)>0?asset('images/buses/').$trip->bus->images[0]->image_name:asset('images/buses/').'/no_picture.png'}}"
                                                     alt="...">
                                            </a>
                                        </td>
                                        <td>{{$trip->date}}</td>
                                        <td>{{$trip->bus->merchant->name}}</td>
                                        <td>{{$trip->bus->reg_number}}</td>
                                        <td>{{$trip->from}}</td>
                                        <td>{{$trip->to}}</td>
                                        <td>{{$trip->price}}</td>
                                        <td>{{$trip->depart_time}}</td>
                                        <td>{{$trip->arrival_time}}</td>
                                        <td>
                                            <form role="form" method="get"
                                                  action="{{route('booking.buses.select', [$trip->bus->id,$trip->schedule_id,$trip->trip_id])}}"
                                                  accept-charset="UTF-8">
                                                @csrf
                                                <input type="hidden" name="date" value="{{$trip->date}}">
                                                <button class="btn btn-primary  btn-sm" type="submit"> Select</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        @else
                            <div class="alert alert-warning"> No routes has being found</div>
                        @endif
                    @else
                        @if(isset($date_error))
                            <div class="alert alert-danger"> Error: Date submitted was invalid, date can not be less
                                than today
                            </div>
                        @endif
                    @endif
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
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
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
    {{--<script src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous">

    </script>--}}


@endsection

@section('import_js')
    <script>
        $(function() {
            $('#busPicturesModal').on("show.bs.modal", function (e) {
                $("#busPicturesModalLabel").html($(e.relatedTarget).data('title'));
                var images = $(e.relatedTarget).data('images');
                console.log(images);
                $('#imageViewer').html('');
                images.forEach(function(item, index){
                    if(index === 0){
                        $('#imageViewer').append('<div class="carousel-item active">\n' +
                            '<img style="width:500px;height: 300px" src="'+item+'" alt="...">\n' +
                            '</div>');
                    } else {
                        $('#imageViewer').append('<div class="carousel-item">\n' +
                            '<img style="width:500px;height: 300px" src="'+item+'" alt="...">\n' +
                            '</div>');
                    }
                });
            });
        });
    </script>
@endsection