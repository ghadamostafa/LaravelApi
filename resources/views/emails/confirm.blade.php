
@component('mail::message')
# welcome {{$user->name}}

Your email has been updated ,please verify it using the button below:

@component('mail::button', ['url' => route('verify',$user->verification_token)])
verify
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent