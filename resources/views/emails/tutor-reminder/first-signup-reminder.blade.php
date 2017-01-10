@extends('emails._.layouts.default-plain')

@section('body')
	<p>
		Hi {{$tutor->first_name}},
		<br>
		<br>
		<b>Thanks for signing up to tutor with us at Tutora.co.uk - complete your profile to join our team!</b>
		<br>
		<br>
	If you are no longer interested, just <a href="{{ route('unsubscribe', [
	'token' => $tutor->admin->subscription_token, 
	'list' => $list]) 
}}">click here to stop receiving these messages</a>
		<br>
		<br>
		We’re excited about adding you to our team of tutors, but we first need you to complete your profile. We make sure that every profile is fully completed before our tutors go live, as that gives you the best chance of gaining new students, who can book in confidence when they know all of your teaching or tutoring experience.
		<br>
		<br>
		Just visit <a href="{{route('tutor.profile.show', ['uuid' => $tutor->uuid]) }}">this link</a> to add to your profile. Click ‘Go Live’ when you’ve finished and we will give you some tips to get the most students possible.
	
		<br>
		<br>
		Here are the sections you still need to complete and some of our top tips to help you with each one:
	</p>
	@include('emails._.partials.requirements')

	<p>If you need a hand completing your profile, just get in touch.
		<br>
		<br>
	
		Kind regards,
		<br>
		The Tutora Team
	</p>

@stop