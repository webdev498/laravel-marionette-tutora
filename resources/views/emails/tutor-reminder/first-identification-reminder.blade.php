@extends('emails._.layouts.default-plain')

@section('body')

<p>
	Hello {{$tutor->first_name}},
	<br>
	<br>
	Thank you for signing up to Tutora. You can access your profile using <a href="{{route('tutor.profile.show', ['uuid' => $tutor->uuid]) }}">this link</a> to add to your profile. All we need to complete your profile is a copy of your ID. 
	<br>
	<br>
	You can either use a passport or a driver license. If you need a hand, just get in touch and we'll be happy to help. 
	<br>
	<br>
	If you are no longer interested, just <a href="{{ route('unsubscribe', [
	'token' => $tutor->admin->subscription_token, 
	'list' => $list]) 
}}">click here to let us know</a> 
	<br>
	<br>
	Kind regards,
	<br>
	The Tutora Team
</p>


@stop