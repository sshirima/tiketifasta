$( document ).ready(function() {

    $(function() {
        $('#route_id').change(function(){
            $('.locations div:first').remove();

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
            });
        });
    });
});
