@extends('emails._.layouts.default-plain')

@section('body')

<p>
	Hello {{$tutor->first_name}},
	<br>
	<br>
	<b>ONE MORE WEEK TO JOIN OUR TEAM: FINAL CHANCE TO COMPLETE YOUR PROFILE!</b>
	<br>
	<br>
	If you are no longer interested, just <a href="{{ route('unsubscribe', [
	'token' => $tutor->admin->subscription_token, 
	'list' => $list]) 
}}">click here to let us know</a> 
	<br>
	<br>
	At Tutora, we’re looking for the most enthusiastic, professional tutors to join our team. We only want to offer our students the more reliable tutors around, so we’re going to give you one more week to complete your profile. Unless we hear from you in that time, we won’t be able to accept your application.
	<br>
	<br>
	It would be great to add you to our team, so please complete your tutor profile by completing the following, missing sections. You can view and edit your profile <a href="{{route('tutor.profile.show', ['uuid' => $tutor->uuid]) }}">here</a>.
</p>

@include('emails._.partials.requirements')

<p>
	Remember, we’re always here to help, so just let us know if there’s anything you’re struggling with and we can help you out!
	<br>
	<br>

	Kind regards,
	<br>
	The Tutora Team
</p>
@stop

