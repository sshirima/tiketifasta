function renderFullCalendarEvents(schedules, direction) {
    $.each(schedules, function (index, schedule) {
        var color = direction === 'GO' ? '#00a65a' : '#00c0ef';
        var event = {
            title: schedule.title,
            start: new Date(schedule.start + ' ' + schedule.depart_time),
            end: new Date(schedule.end + ' ' + schedule.arrival_time),
            backgroundColor: color,
            borderColor: color
        };

        $('#calendar').fullCalendar('renderEvent', event, true);
    });
}

$(document).ready(function () {

    $( ".datepicker" ).datepicker('setDate', "11/6/2018");



    $('#direction').change(function () {

        $('.route-trips').remove();
        var isChecked = this.checked;
        var trips = document.getElementById('trips');

        $.each(bus_trips, function (index, trip) {
            var price = '';
            var direction = '';
            var control_direction = '';
            if (isChecked) {
                control_direction = 'GO';
            } else {
                control_direction = 'RETURN';
            }
            if (trip.direction === control_direction){
                if(trip.price === null){
                    price = '<div class="label label-warning">Not set</div>';
                }else{
                    price = trip.price;
                }

                if(trip.direction === 'GO'){
                    direction = '<div class="label label-success"><i class="fas fa-arrow-circle-right"></i> Going</div>';
                }else{
                    direction = '<div class="label label-info"><i class="fas fa-arrow-circle-left"></i> Return</div>';
                }
                var route_trip = document.createElement("tr");
                route_trip.setAttribute("class", "route-trips");
                route_trip.innerHTML = '<td>' + trip.from.name + '</td>' +
                    '<td>' + trip.to.name + '</td>' +
                    '<td>' + trip.depart_time + '</td>' +
                    '<td>' + trip.arrival_time + '</td>' +
                    '<td>' + trip.travelling_days + '</td>' +
                    '<td>' + price + '</td>'+
                    '<td>' + direction + '</td>'
                ;
                trips.appendChild(route_trip);
            }
        });

        //Remove existing events
        $('#calendar').fullCalendar('removeEvents');
        var direction = '';

        if($('#direction').prop('checked')){
            direction = 'GO'
        }else{
            direction = 'RETURN'
        }

        $.ajax({
            type: 'GET',
            url: 'schedules/events',
            data: {
                'direction': direction,

                '_token': $('input[name=_token]').val()
            },
            success: function(schedules, textStatus, xhr)  {
                $.toaster({ priority : 'success', title : 'Success', message : 'Retrieved...'});

                renderFullCalendarEvents(schedules, direction);
            },
            statusCode: {
                404: function() {
                    $.toaster({ priority : 'error', title : 'Failed', message : 'Route not found'});
                },
                500: function() {
                    $.toaster({ priority : 'error', title : 'Failed', message : 'Select a route'});
                }
            }
        });

    });

    $(function () {



        /* initialize the calendar
         -----------------------------------------------------------------*/
        //Date for the calendar events (dummy data)
        var date = new Date()
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear()
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            buttonText: {
                today: 'today',
                month: 'month',
                week: 'week',
                day: 'day'
            },
            //Random default events
            events: [
                /*{
                    title: 'Scheduled: Dar - Kilimanjaro',
                    start: new Date(2018, m, d-10,7),
                    end: new Date(2018, m, d-10,15),
                    backgroundColor: '#00a65a', //success
                    borderColor: '#00a65a' //success
                },
                  {
                      title: 'Scheduled: Dar - Arusha',
                      start: new Date(y, m, d-10,7),
                      end: new Date(y, m, d-10,17),
                      backgroundColor: '#00a65a', //red
                      borderColor: '#00a65a' //red
                  },
                  {
                      title: 'Long Event',
                      start: new Date(y, m, 5),
                      backgroundColor: '#f39c12', //yellow
                      borderColor: '#f39c12' //yellow
                  },
                  {
                      title: 'Meeting',
                      start: new Date(y, m, 10, 30),
                      allDay: false,
                      backgroundColor: '#0073b7', //Blue
                      borderColor: '#0073b7' //Blue
                  },
                  {
                      title: 'Lunch',
                      start: new Date(y, m, d, 12, 0),
                      end: new Date(y, m, d, 14, 0),
                      allDay: false,
                      backgroundColor: '#00c0ef', //Info (aqua)
                      borderColor: '#00c0ef' //Info (aqua)
                  },
                  {
                      title: 'Birthday Party',
                      start: new Date(y, m, d + 1, 19, 0),
                      end: new Date(y, m, d + 1, 22, 30),
                      allDay: false,
                      backgroundColor: '#00a65a', //Success (green)
                      borderColor: '#00a65a' //Success (green)
                  },
                  {
                      title: 'Click for Google',
                      start: new Date(y, m, 28),
                      end: new Date(y, m, 29),
                      url: 'http://google.com/',
                      backgroundColor: '#3c8dbc', //Primary (light-blue)
                      borderColor: '#3c8dbc' //Primary (light-blue)
                  }*/
            ],
            editable: false,
            droppable: false, // this allows things to be dropped onto the calendar !!!

        });

        renderFullCalendarEvents(schedules, 'GO');
    })
});