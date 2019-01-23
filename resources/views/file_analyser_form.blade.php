<html lang="en"><head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Form</title>
</head>

<body>
{{--{{Form::open(array('url' => route('file_analyser_analyse'),'files'=>'true'))}}
<label >Select the file to upload</label>
{{Form::file('cisco_config_file')}}
{{Form::submit('Upload File')}}
{{Form::close()}}--}}
Configuration analyser, click to run: <a href="{{route('file_analyser_analyse')}}"><button>Execute</button></a>

</body>
