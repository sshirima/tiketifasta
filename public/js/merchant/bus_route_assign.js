var selectedLoc;
var room = 1;
var fieldcount = 0;

function timePickerInitialize() {

    $('.timepicker').timepicker({
        showInputs: false,
        timeFormat: 'HH:mm'
    });
}

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

    $('button[name*=update-time-]').on('click', function () {
        var tripId = $(this).val();

        $('.trip-time-'+tripId).prop('disabled',false).timepicker({
            showInputs: false,
            timeFormat: 'HH:mm'
        });

        $(this).removeClass('btn-primary').addClass('btn-success').html('Save').attr('onclick','updateTripTime('+ tripId +');');
    });

});

function updateTripTime(tripId) {

    var depart_time = $('#depart_time_'+tripId).val();
    var arrival_time = $('#arrival_time_'+tripId).val();

    if (depart_time === '' || arrival_time === ''){
        $.toaster({ priority : 'error', title : 'Error', message : 'Arrival time or Depart time not specified'});
    }else {
        $.toaster({ priority : 'success', title : 'Success', message : 'Updating trip time'});

        $.ajax({
            type: 'POST',
            url: 'trip/'+tripId+'/update/time',
            data: {
                'depart_time': depart_time,
                'arrival_time': arrival_time,
                '_token': $('input[name=_token]').val()
            },
            success: function(trip, textStatus, xhr)  {

                if(trip.errors){
                    var msg = '';
                    if(trip.errors.arrival_time){
                        msg = msg + trip.errors.arrival_time[0]+'<br>';
                    }
                    if(trip.errors.depart_time){
                        msg = msg +  trip.errors.depart_time[0];
                    }

                    if(msg===''){
                        $.toaster({ priority : 'error', title : 'Failed', message : 'Failed to process the request'});
                    } else {
                        $.toaster({ priority : 'error', title : 'Failed', message : msg});
                    }

                }

                if(trip === false){
                    $.toaster({ priority : 'error', title : 'Failed', message : 'Failed to update trip time'});
                } else {
                    $.toaster({ priority : 'success', title : 'Success', message : 'Trip time has been updated!'});
                    $('#arrival_time_'+tripId).val(trip.arrival_time);
                    $('#depart_time_'+tripId).val(trip.depart_time);
                    $('#update-time-'+tripId).removeClass('btn-success').addClass('btn-primary').html('Update').removeAttr('onclick');
                    $('input[id=depart_time_'+tripId+']').prop('disabled',true);
                    $('input[id=arrival_time_'+tripId+']').prop('disabled',true);
                    console.log(trip)
                }


            },
            statusCode: {
                404: function() {
                    $.toaster({ priority : 'error', title : 'Failed', message : 'Route not found'});
                },
                500: function() {
                    $.toaster({ priority : 'error', title : 'Failed', message : 'Select a route'});
                },
                422: function() {
                    $.toaster({ priority : 'error', title : 'Failed', message : 'Unprocessable Entity'});
                }
            }
        });
    }
}

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

    timePickerInitialize();
}

function removeTrip(rid) {
    fieldcount--;
    $('.removeclass'+rid).remove();

}