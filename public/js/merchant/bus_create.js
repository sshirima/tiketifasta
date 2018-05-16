$( document ).ready(function() {

    //Adding operation dates
    $(function()
    {
        $(document).on('click', '.btn-add', function(e)
        {
            e.preventDefault();

            var controlForm = $('.trip_dates div:first'),
                currentEntry = $(this).parents('.entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(controlForm);

            newEntry.find('input').val('');
            controlForm.find('.entry:not(:last) .btn-add')
                .removeClass('btn-add').addClass('btn-remove')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="glyphicon glyphicon-minus"></span>');
        }).on('click', '.btn-remove', function(e)
        {
            $(this).parents('.entry:first').remove();

            e.preventDefault();
            return false;
        });
    });

    $('.date').datepicker({
        multidate: true,
        format: 'yyyy-mm-dd'
    });


    //Adding bus routes
    $(function() {
        $('#route_id').change(function(){
            /*('.locations div:first').remove();
            var route_destinations = destinations[$(this).val()]['destinations'];
            var destination_length = route_destinations.length;
            $('.locations').html('<div class="destinations"></div>');
            var start =  destinations[$(this).val()]['start_location']
            var lastdestination = route_destinations[destination_length-1];
            $('.locations .destinations').append('' +
                '<div class="form-group">\n' +
                '        <label class="col-sm-5 control-label" for="location'+start.id+'" id="location">'+start.name+'</label>\n' +
                '        <div class="col-sm-7 " id="location'+start.id+'">\n' +
                '            <div class="form-group">\n' +
                '                <div class="control-label col-sm-4" >Departure time</div>\n' +
                '                <div class="col-sm-8">\n' +
                '                    <input class="form-control" type="time" value="" name="timetables['+lastdestination.location_id+'][departure_time]" placeholder="Time...">\n' +
                '                </div>\n' +
                '            </div>\n' +
                '        </div>\n' +
                '    </div>');

            $.each(route_destinations, function(index, value) {
                var isLastElement = index == route_destinations.length -1;
                 if (isLastElement) {
                    $('.locations .destinations').append('' +
                        '<div class="form-group">\n' +
                        '        <label class="col-sm-5 control-label" for="location'+value.location_id+'" id="location">'+value.location_name+'</label>\n' +
                        '        <div class="col-sm-7 " id="location'+value.location_id+'">\n' +
                        '            <div class="form-group">\n' +
                        '                <div class="control-label col-sm-4">Arrival time</div>\n' +
                        '                <div class="col-sm-8">\n' +
                        '                    <input class="form-control" type="time" name="timetables['+value.location_id+'][arrival_time]" placeholder="Time...">\n' +
                        '                </div>\n' +
                        '            </div>\n' +
                        '        </div>\n' +
                        '    </div>');
                }  else {
                    $('.locations .destinations').append('' +
                        '<div class="form-group">\n' +
                        '        <label class="col-sm-5 control-label" for="location'+value.location_id+'" id="location">'+value.location_name+'</label>\n' +
                        '        <div class="col-sm-7 " id="location'+value.location_id+'">\n' +
                        '            <div class="form-group">\n' +
                        '                <div class="control-label col-sm-4" >Arrival time</div>\n' +
                        '                <div class="col-sm-8">\n' +
                        '                    <input class="form-control" type="time" value="" name="timetables['+value.location_id+'][arrival_time]" placeholder="Time...">\n' +
                        '                </div>\n' +
                        '            </div>\n' +
                        '            <div class="form-group">\n' +
                        '                <div class="control-label col-sm-4">Departure time</div>\n' +
                        '                <div class="col-sm-8">\n' +
                        '                    <input class="form-control" type="time" name="timetables['+value.location_id+'][departure_time]" placeholder="Time...">\n' +
                        '                </div>\n' +
                        '            </div>\n' +
                        '        </div>\n' +
                        '    </div>');
                }
            });*/
            routeId = $('#route_id').val();
            selectedRoute = routes[routeId];
            selectedLocations = selectedRoute.locations;
            selectedLoc = '';
            $.each(selectedLocations, function(i, location)
            {
                selectedLoc = selectedLoc+'<option value="'+location.id+'">'+location.name+'</option>';

            });
            console.log(selectedLoc);
            onRouteSelected();
        });
    });

    //Adding from
});

var locations;
var selectedLoc;
var room = 1;
function addTrip() {
    room++;
    var objTo = document.getElementById('trip_fields');
    var divTest = document.createElement("div");
    divTest.setAttribute("class", "form-group routeTrip removeclass"+room);
    var rdiv = 'removeclass'+room;
    divTest.innerHTML = '<div class="col-sm-3">\n' +
        '                        <label class="control-label">From:</label>\n' +
        '                        <select class="form-control" id="educationDate" name="source[]">\n' +
        '                               ' +selectedLoc+
        '                        </select>\n' +
        '                    </div>\n' +
        '                    <div class="col-sm-3">\n' +
        '                        <label class="control-label">To:</label>\n' +
        '                        <select class="form-control" id="educationDate" name="destination[]">\n' +
        '                               ' +selectedLoc+
        '                        </select>\n' +
        '                    </div>\n' +
        '                    <div class="col-sm-2">\n' +
        '                        <label class="control-label">Depart time:</label>\n' +
        '                        <input class="form-control" type="time" value="" name="depart_time[]" placeholder="Time...">\n' +
        '                    </div>\n' +
        '                    <div class="col-sm-2">\n' +
        '                        <label class="control-label">Arrival time:</label>\n' +
        '                        <input class="form-control" type="time" value="" name="arrival_time[]" placeholder="Time...">\n' +
        '                    </div>\n' +
        '                    <div class="col-sm-2 form-group">\n' +
        '                        <label class="control-label">Travelling days:</label>\n' +
        '                        <div class="input-group">\n' +
        '                            <select class="form-control" id="educationDate" name="travelling_days[]">\n' +
        '                                <option value="1">1</option>\n' +
        '                                <option value="2">2</option>\n' +
        '                                <option value="3">3</option>\n' +
        '                                <option value="4">4</option>\n' +
        '                            </select>\n' +
        '                            <div class="input-group-btn">\n' +
        '                                <button class="btn btn-danger" type="button"  onclick="removeTrip('+ room +');"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> </button>\n' +
        '                            </div>\n' +
        '                        </div>\n' +
        '                    </div>';

    objTo.appendChild(divTest)
}

function onRouteSelected() {
    $('.routeTrip').remove();
    var objTo = document.getElementById('trip_fields');
    var divTest = document.createElement("div");
    divTest.setAttribute("class", "form-group routeTrip");
    divTest.innerHTML = '<div class="col-sm-3">\n' +
        '                        <label class="control-label">From:</label>\n' +
        '                        <select class="form-control" id="educationDate" name="source[]">\n' +
        '                            ' +selectedLoc+
        '                        </select>\n' +
        '                    </div>\n' +
        '                    <div class="col-sm-3">\n' +
        '                        <label class="control-label">To:</label>\n' +
        '                        <select class="form-control" id="educationDate" name="destination[]">\n' +
        '                            ' +selectedLoc+
        '                        </select>\n' +
        '                    </div>\n' +
        '                    <div class="col-sm-2">\n' +
        '                        <label class="control-label">Depart time:</label>\n' +
        '                        <input class="form-control" type="time" value="" name="depart_time[]" placeholder="Time...">\n' +
        '                    </div>\n' +
        '                    <div class="col-sm-2">\n' +
        '                        <label class="control-label">Arrival time:</label>\n' +
        '                        <input class="form-control" type="time" value="" name="arrival_time[]" placeholder="Time...">\n' +
        '                    </div>\n' +
        '                    <div class="col-sm-2 form-group">\n' +
        '                        <label class="control-label">Travelling days:</label>\n' +
        '                        <div class="input-group">\n' +
        '                            <select class="form-control" id="educationDate" name="travelling_days[]">\n' +
        '                                <option value="1">1</option>\n' +
        '                                <option value="2">2</option>\n' +
        '                                <option value="3">3</option>\n' +
        '                                <option value="4">4</option>\n' +
        '                                <option value="5">5</option>\n' +
        '                            </select>\n' +
        '                            <div class="input-group-btn">\n' +
        '                                <button class="btn btn-success" type="button"  onclick="addTrip();"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> </button>\n' +
        '                            </div>\n' +
        '                        </div>\n' +
        '                    </div>';

    objTo.appendChild(divTest)
}

function removeTrip(rid) {
    $('.removeclass'+rid).remove();
}
