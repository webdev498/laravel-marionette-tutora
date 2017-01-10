@extends('emails._.layouts.default-plain')

@section('body')

<p>
	Hello {{$tutor->first_name}},
	<br>
	<br>
	It would be brilliant to get you tutoring, so just complete the remaining sections of your profile and we’ll get you started straight away.
	<br>
	<br>
	If you are no longer interested, just <a href="{{ route('unsubscribe', [
	'token' => $tutor->admin->subscription_token, 
	'list' => $list]) 
}}">click here to let us know</a>
</p>

@include('emails._.partials.requirements')

<p>
	Log in at <a href="{{route('tutor.profile.show', ['uuid' => $tutor->uuid]) }}">Tutora.co.uk/Login</a> to complete your profile. Don’t forget to click ‘Go Live’ when you’re done.

	<br>
	<br>

	Kind regards,
	<br>
	The Tutora Team
</p>


@stop