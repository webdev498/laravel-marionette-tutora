@extends('emails._.layouts.default-plain')

@section('body')

<p>
	Hi {{$tutor->first_name}},
	<br>
	<br>
	We still need a copy of your Identification before we can start you tutoring with us. You can either upload it directly using <a href="{{route('tutor.profile.show', ['uuid' => $tutor->uuid]) }}">this link</a>, or attach it to a reply to this email and I'll upload it for you.
	<br>
	<br>
	This can either be a passport or a driver license. You can either upload it directly to your account by logging in and clicking "Account" and then "Identification", or send it to us by replying to this email.
	<br>
	<br>
	If you are no longer interested, just <a href="{{ route('unsubscribe', [
	'token' => $tutor->admin->subscription_token, 
	'list' => $list]) 
}}">click here to let us know</a>
	<br>
	<br>
	If you need a hand completing your profile, just get in touch.
	<br>
	<br>

	Kind regards,
	<br>
	The Tutora Team
</p>


@stop