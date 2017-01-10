@extends('emails._.layouts.default-plain')

@section('body')


<p>
	Hi {{$tutor->first_name}},
	<br>
	<br>
	It’s been a few days since you signed up to start tutoring with us. We’d love to get you started as a tutor, but you still have a few sections to complete before we can match you with our students. Just visit <a href="{{route('tutor.profile.show', ['uuid' => $tutor->uuid]) }}">this link</a> to log in and complete your profile. Be sure to click ‘Go Live’ when you’re ready to tutor.
	<br>
	<br>
	If you are no longer interested, just <a href="{{ route('unsubscribe', [
	'token' => $tutor->admin->subscription_token, 
	'list' => $list]) 
}}">click here to let us know</a>

	<br>
	<br>
	We still need the following information:
</p>
@include('emails._.partials.requirements')

<p>
	If you need any help, just get in touch and we’ll be happy to help!
	<br>
	<br>
	Kind regards,
	<br>
	The Tutora Team
</p>

@stop