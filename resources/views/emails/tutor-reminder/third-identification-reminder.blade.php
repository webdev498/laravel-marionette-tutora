@extends('emails._.layouts.default-plain')

@section('body')

<p>
	Hello {{$tutor->first_name}},
	<br>
	<br>
	I wanted to check if you had found time to upload a copy of your ID? As you might working with children, we require a copy of your ID to verify your identity. This can either be a passport or a driving license.
	<br>
	<br>
	You can access your profile using <a href="{{route('tutor.profile.show', ['uuid' => $tutor->uuid]) }}">this link</a> to upload your ID there, or if it's easier just send it to this email address. 
	<br>
	<br>
	If you are no longer interested, just <a href="{{ route('unsubscribe', [
	'token' => $tutor->admin->subscription_token, 
	'list' => $list]) 
}}">click here to let us know</a>

	<br>
	<br>
	If you need a hand, just get in touch.
	<br>
	<br>

	Kind regards,
	<br>
	The Tutora Team
</p>


@stop