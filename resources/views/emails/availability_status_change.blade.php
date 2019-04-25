@component('mail::message')
# Third part server status

@component('mail::panel', ['url' => ''])
    Server name: {{$parameters['server_name']}}<br>
    Status: {{$parameters['availability_status']}}
@endcomponent

{{ config('app.name') }}
@endcomponent
