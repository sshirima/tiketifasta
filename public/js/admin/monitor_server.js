$( document ).ready(function () {

    timeout = server_ips.length;
    $('#system_status').html('<div class="fa-1x"><i class="fas fa-spinner fa-pulse"></i> Running system check</div>');
    server_ips.forEach(function (item, index) {
        pingServerIp(item);
    });

});
var responses = 0;
var success = 0;
var timeout = 0;

function pingServerIp(ip) {

    $.ajax({
        type: 'POST',
        url: 'admin/monitor/server',
        data: {
            'ip': ip,
            '_token': $('input[name=_token]').val()
        },
        success: function(data, status, xhr){
            responses++;
            console.log(data);

            if(data.status === true){
                success++;
                timeout--;
                $('#report_summary').append('<div class="fa-1x"><i class="fas fa-check-circle"></i>'+ ' '+servers[ip].name+ ' reachable <br>');
            } else {
                $('#report_summary').append('<div class="fa-1x"><i class="fas fa-times"></i>'+ ' '+ servers[ip].name+ ' timeout <br>');
            }

            if(responses === server_ips.length){
                $('#system_status').html('<div class="fa-1x"><i class="fas fa-check"></i> Check completed<br>=============</div>');
            }

            var health = Math.round((success)/(success+timeout)*100);

            if (health >= 50 && health <100){
                $('#health_box').removeClass('bg-red').addClass('bg-yellow');
            }
            if (health === 100){
                $('#health_box').removeClass('bg-yellow').addClass('bg-green');
            }

            $('#health_percent').html(health+'%');
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
}