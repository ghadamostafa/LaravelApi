

@component('mail::message')
# welcome {{$user->name}}

please verify your mail using the button below:

@component('mail::button', ['url' => route('verify',$user->verification_token)])
verify
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent