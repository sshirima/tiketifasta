<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
        var directories;
        var file_index =0;

        function getMessage() {
            $("#msg").html('Fetching directories...');
            display_c(86501);
            $.ajax({
                type: 'GET',
                url: '/get-directories',
                data: '_token = <?php echo csrf_token() ?>',
                success: function (data) {
                    directories = JSON.parse(data);
                    $("#msg").html(
                        directories.length+ ' directories found <br>'
                    );

                    analyseConfigFiles(file_index);
                }
            });
        }

        function analyseConfigFiles(index) {

            /*directories.foreach(function(item, index){
                console.log(item);
            });*/
            var report;

            $("#msg").html(
                'Parsing config file: '+directories[index]+'... <br>'
            );

            $.ajax({
                type: 'GET',
                url: '/analyse-config-file',
                data: {
                    _token : '<?php echo csrf_token() ?>',
                    filename: directories[file_index],
                    new_file: false
                },
                success: function (data) {
                    report = JSON.parse(data);
                    file_index = index+1;
                    /*if(index === 500){
                        timerStop();
                    }else*/
                    if((file_index in directories)){
                        $("#msg").append('Analysed: '+
                            report.router.parsed_interfaces.length+' parsed interfaces found, '+
                            report.router.parsed_service_instances.length+' parsed service instances found, '+
                            report.router.service_instances.length+' service instances found, '+
                            report.router.interfaces.length+' interfaces found '+ '<br>');
                        //console.log(report.router.interfaces.length);
                        analyseConfigFiles(index+1)
                    } else {
                        timerStop();
                    }
                },
                fail: function(xhr, textStatus, errorThrown){
                    console.log('Error');
                }
            });
        }



        var mytime;
        var counter = 0;

        function display_c (start) {
            window.start = parseFloat(start);
            var end = 0 // change this to stop the counter at a higher value
            var refresh = 1000; // Refresh rate in milli seconds
            if( window.start >= end ) {
                counter++;
                mytime = setTimeout( 'display_ct()',refresh )
            } else {
                alert("Time Over ");
            }
        }

        function display_ct () {
            // Calculate the number of days left
            var days = Math.floor(window.start / 86400);
            // After deducting the days calculate the number of hours left
            var hours = Math.floor((window.start - (days * 86400 ))/3600)
            // After days and hours , how many minutes are left
            var minutes = Math.floor((window.start - (days * 86400 ) - (hours *3600 ))/60)
            // Finally how many seconds left after removing days, hours and minutes.
            var secs = Math.floor((window.start - (days * 86400 ) - (hours *3600 ) - (minutes*60)))

            var x = window.start + "(" + days + " Days " + hours + " Hours " + minutes + " Minutes and " + secs + " Secondes " + ")";

            /*document.getElementById('ct').innerHTML = x;
            window.start = window.start - 1;*/

            var y = counter;
            var min = Math.floor(counter/60);
            var hrs = Math.floor(counter/60/60);
            var dys = Math.floor(counter/60/60/24);

            if (min === 0){
                y = counter+' secs, ';
            } else if (min > 0){
                sec = counter%60;
                y = min+' mins, '+counter%60+' secs, ';
            } else if (min > 0 && hrs > 0){
                y =  dys+' hrs, '+ counter%3600+' mins, '+counter%60+' secs, ';
            } else {
                y = counter+' secs, ';
            }
            document.getElementById('ct').innerHTML = y+file_index+' routers analysed';
            tt = display_c(window.start);
        }

        function timerStop() {
            clearTimeout(mytime);
        }
    </script>
    <title>Result</title>
</head>

<body>
<div id='msg'></div>
<div id='ct' ></div>
<?php
echo Form::button('Run', ['onClick' => 'getMessage()']);
?>
</body>

