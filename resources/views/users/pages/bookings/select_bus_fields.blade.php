<table class="table">
    <thead>
    <tr>
        <th>Company name</th>
        <th>Reg #</th>
        <th>Depart time</th>
        <th>Arrival time</th>
        <th>Price</th>
    </tr>
    </thead>
    <tbody>
    @foreach($buses as $bus)
        <tr>
            <td>{{$bus->merchant_name}}</td>
            <td>{{$bus->reg_number}}</td>
            <td>{{$bus->depart_time}}</td>
            <td>{{$bus->arrival_time}}</td>
            <td>{{$bus->price}}</td>
            <td class="pull-right">
                {!! Form::open(['route' => ['booking.select.seats'], 'method' => 'get']) !!}
                <input hidden name="timetable_id" value="{{$bus->timetable_id}}">
                <input hidden name="bus_id" value="{{$bus->bus_id}}">
                <input hidden name="journey" value="{{json_encode($journey)}}">
                <div class='btn-group'>
                    <button type="submit" class="btn btn-primary">Select this</button>
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>