<table class="table table-striped">
    <thead  class="thead-light">
    <tr>
        <th>Route name</th>
        <th>Ticket type</th>
        <th>Price</th>
        <th>Last updated</th>
    </tr>
    </thead>
    <tbody>
    @foreach($timetable->ticketPrices as $ticketPrice)
        <tr>
            <td>{{$busRoute->start_location->name.' to '.$ticketPrice->name}}</td>
            <td>{{$ticketPrice->ticket_type}}</td>
            <td>{{$ticketPrice->price}}</td>
            <td>{{$ticketPrice->updated_at}}</td>
            <td class="pull-right">
                {!! Form::open(['route' => ['merchant.ticket_price.delete', $bus->id,$ticketPrice->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure you want to delete ".$ticketPrice->ticket_type." ticket price for the route: ".$bus->start_location.' to '.$ticketPrice->name."?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>