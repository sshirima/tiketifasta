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
                                        <td>{{$trip->bus->merchant->name}}</td>
                                        <td>{{$trip->bus->reg_number}}</td>
                                        <td>{{$trip->from}}</td>
                                        <td>{{$trip->to}}</td>
                                        <td>{{$trip->price}}</td>
                                        <td>{{$trip->depart_time}}</td>
                                        <td>{{$trip->arrival_time}}</td>
                                        <td><form role="form" method="get"
                                                   action="{{route('booking.buses.select', [$trip->bus->id,$trip->schedule_id,$trip->trip_id])}}"
                                                   accept-charset="UTF-8">
                                                @csrf
                                                <input type="hidden" name="date" value="{{$trip->date}}">
                                                <button class="btn btn-primary  btn-sm" type="submit"> Select </button>
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
                        <div class="alert alert-warning"> No schedules</div>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection

@section('import_css')

@endsection

@section('import_js')

@endsection