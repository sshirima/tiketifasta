@component('mail::message')
# Registration completed

Dear {{$merchant->name}},<br>
    Your account has been created, please find your below credentials for login.

@component('mail::panel', ['url' => ''])
    Username: {{$account->email}}<br>
    Password: {{$password}}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
