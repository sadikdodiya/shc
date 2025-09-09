@component('mail::message')
# Verify Email Address

Thank you for signing up with {{ config('app.name') }}! Please click the button below to verify your email address.

@component('mail::button', ['url' => $url])
Verify Email Address
@endcomponent

If you did not create an account, no further action is required.

Thanks,<br>
{{ config('app.name') }}

@slot('subcopy')
If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:

[{{ $url }}]({{ $url }})
@endslot
@endcomponent
