var selectedLoc;
var room = 1;
var fieldcount = 0;
var route_trip_source_node;

$( document ).ready(function () {
    $('#route_id').change(function () {

        $.ajax({
            type: 'GET',
            url: 'assign/'+$('#route_id').val()+'/locations',
            data: {
                '_token': $('input[name=_token]').val()
            },
            success: function(locations, textStatus, xhr) {
                $.toaster({ priority : 'success', title : 'Success', message : 'Locations loaded'});

                selectedLoc = '<option value="0">Select location</option>';
                $.each(locations, function(i, location)
                {
                    selectedLoc = selectedLoc+'<option value="'+location.id+'">'+location.name+'</option>';

                });

                showRouteTrips();
            },
            statusCode: {
                404: function() {
                    $.toaster({ priority : 'error', title : 'Failed', message : 'Route not found'});
                },
                500: function() {
                    $.toaster({ priority : 'error', title : 'Failed', message : 'Select a route'});
                    $('.route-trips').remove();
                }
            }
        });
    });

});

function showRouteTrips() {
    $('.route-trips').remove();
    var trips = document.getElementById('trips');
    var route_trip = document.createElement("div");
    route_trip.setAttribute("class", "form-group route-trips");
    route_trip.innerHTML = '<div class="col-sm-3">\n' +
        '                        <label class="control-label">From:</label>\n' +
        '                        <select class="form-control" id="educationDate" name="trips['+fieldcount+'][source]">\n' +
        '                            ' +selectedLoc+
        '                        </select>\n' +
        '                    </div>\n' +
        '<div class="col-sm-3">\n' +
        '                        <label class="control-label">To:</label>\n' +
        '                        <select class="form-control" id="educationDate" name="trips['+fieldcount+'][destination]">\n' +
        '                            ' +selectedLoc+
        '                        </select>\n' +
        '                    </div>\n' +
        '<div class="col-sm-2 bootstrap-timepicker">\n' +
        ' <label >Depart time:</label>\n' +
        ' <input type="text" class="form-control timepicker" name="trips['+fieldcount+'][depart_time]">\n' +
        '</div>\n' +
        '<div class="col-sm-2 bootstrap-timepicker">\n' +
        ' <label >Arrival time:</label>\n' +
        '  <input type="text" class="form-control timepicker" name="trips['+fieldcount+'][arrival_time]">\n' +
        '</div>\n' +
        '                    <div class="col-sm-2 form-group">\n' +
        '                        <label class="control-label">Travelling days:</label>\n' +
        '                        <div class="input-group">\n' +
        '<select class="form-control" id="educationDate" name="trips['+fieldcount+'][travelling_days]">\n' +
        '                                <option value="1">1</option>\n' +
        '                                <option value="2">2</option>\n' +
        '                                <option value="3">3</option>\n' +
        '                                <option value="4">4</option>\n' +
        '                                <option value="5">5</option>\n' +
        '                            </select>\n'+
        '                            <div class="input-group-btn">\n' +
        '                                <button class="btn btn-success" type="button"  onclick="addTrip();"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> </button>\n' +
        '                            </div>\n' +
        '                        </div>\n' +
        '                    </div>';

    trips.appendChild(route_trip);

    $('.timepicker').timepicker({
        showInputs: false,
        timeFormat: 'HH:mm',
    });

}

function addTrip() {
    room++;
    fieldcount++;
    var trips = document.getElementById('trips');
    var new_route_trip = document.createElement("div");
    new_route_trip.setAttribute("class", "form-group route-trips removeclass"+room);
    new_route_trip.innerHTML = '<div class="col-sm-3">\n' +
        '                        <label class="control-label">From:</label>\n' +
        '                        <select class="form-control" id="educationDate" name="trips['+fieldcount+'][source]">\n' +
        '                            ' +selectedLoc+
        '                        </select>\n' +
        '                    </div>\n' +
        '<div class="col-sm-3">\n' +
        '                        <label class="control-label">To:</label>\n' +
        '                        <select class="form-control" id="educationDate" name="trips['+fieldcount+'][destination]">\n' +
        '                            ' +selectedLoc+
        '                        </select>\n' +
        '                    </div>\n' +
        '<div class="col-sm-2 bootstrap-timepicker" >\n' +
        ' <label >Depart time:</label>\n' +
        ' <input type="text" class="form-control timepicker" name="trips['+fieldcount+'][depart_time]">\n' +
        '</div>\n' +
        '<div class="col-sm-2 bootstrap-timepicker">\n' +
        ' <label >Arrival time:</label>\n' +
        '  <input type="text" class="form-control timepicker" name="trips['+fieldcount+'][arrival_time]">\n' +
        '</div>\n' +
        '                    <div class="col-sm-2 form-group">\n' +
        '                        <label class="control-label">Travelling days:</label>\n' +
        '                        <div class="input-group">\n' +
        '<select class="form-control" id="educationDate" name="trips['+fieldcount+'][travelling_days]">\n' +
        '                                <option value="1">1</option>\n' +
        '                                <option value="2">2</option>\n' +
        '                                <option value="3">3</option>\n' +
        '                                <option value="4">4</option>\n' +
        '                                <option value="5">5</option>\n' +
        '                            </select>\n'+
        '                            <div class="input-group-btn">\n' +
        '                                <button class="btn btn-danger" type="button"  onclick="removeTrip('+ room +');"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> </button>\n' +
        '                            </div>\n' +
        '                        </div>\n' +
        '                    </div>';

    trips.appendChild(new_route_trip)

    $('.timepicker').timepicker({
        showInputs: false,
        timeFormat: 'HH:mm'
    });
}

function removeTrip(rid) {
    fieldcount--;
    $('.removeclass'+rid).remove();

}