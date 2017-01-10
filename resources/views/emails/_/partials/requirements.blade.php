<ul>
@foreach($requirements->data as $requirement)

	@if ($requirement->name == 'identification')
		
		<li><b>{{$requirement->title}}</b> We verify the identity of every Tutor who joins us. Upload a colour copy of either your driving licence or passport. Make sure this is a photographic file (i.e. a jpeg, not a pdf) and is nice and clear</li>
	@endif 
	@if ($requirement->name == 'tagline')
		<li><b>{{$requirement->title}}</b> Set a tagline that summarises what you are offering (e.g "Expert Maths Tutor in London")</li>
	@endif 
	@if ($requirement->name == 'bio')
		<li><b>{{$requirement->title}}</b> Write a couple of paragraphs about why you would make a great tutor</li>
	@endif
	@if ($requirement->name == 'subjects')
		<li><b>{{$requirement->title}}</b> Choose the ALL of the subjects AND levels you are happy to teach (not just the highest level)</li>
	@endif
	@if ($requirement->name == 'qualifications')
		<li><b>{{$requirement->title}}</b>  Add all of your relevant qualifications. Use the ‘Other’ section for those that don’t quite fit elsewhere.</li>
	@endif
	@if ($requirement->name == 'travel_policy')
		<li><b>{{$requirement->title}}</b> Tell us where you are based, and how far you are willing to travel</li> 
	@endif
	@if ($requirement->name == 'quiz_questions')
		<li><b>{{$requirement->title}}</b> Take the quiz to complete</li>
	@endif
	@if ($requirement->name == 'rate')
		<li><b>{{$requirement->title}}</b> Choose a price for your services. Bear in mind that we take a small commission from this base rate</li>
	@endif
	@if ($requirement->name == 'profile_picture')
		<li><b>{{$requirement->title}}</b>Upload a photo of yourself: look professional and friendly. We do not accept photos of anything other than you</li>
	@endif
@endforeach
</ul>
