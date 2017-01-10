


	
@if ($results->titles->location && $results->titles->subject)

	@if (\Lang::has("search.header." . location_to_url($results->titles->location) . "." . lcfirst($results->titles->subject)))
		{{trans("search.header." . location_to_url($results->titles->location) . "." . lcfirst($results->titles->subject))}}

	@else
		Looking for a {{ $results->titles->subject }} Tutor in {{ $results->titles->location }}? Whether you are seeking a Primary, GCSE or A-Level {{ $results->titles->subject }} Tutor, we have private tutors available across {{ $results->titles->location }}, for one-to-one tuition in your own home. 

		To find the nearest tutor to you, replace '{{ $results->titles->location }}' with your own postcode and you will be able to see how far each tutor is from your home. 

	@endif
@endif

@if (!$results->titles->location && $results->titles->subject)
	Are you seeking a {{ $results->titles->subject }} Tutor? Tutora can help. Whether you are looking for a Primary, GCSE or A Level {{ $results->titles->subject }} Tutor, we have private tutors available across the country for one-to-one tuition in your own home.

	To find the nearest tutor to you, simply enter your postcode and you will be able to see all our local tutors.
@endif

@if ($results->titles->location && !$results->titles->subject)

	@if (!empty($subjects))
		We have a great team of private tutors across {{ $results->titles->location}}. Whether you are looking for a <a href="{{ route('search.index', ['subject' => 'maths', 'location' => $results->titles->location ])}}">Maths</a>, <a href="{{ route('search.index', ['subject' => 'english', 'location' => $results->titles->location ])}}">English</a>, or <a href="{{ route('search.index', ['subject' => 'combined-sciences', 'location' => $results->titles->location ])}}">Science</a> Tutor, we have private tutors available across the country for one-to-one tuition in your own home. You can view all of our {{ $results->titles->location }} Tutors’ profiles and message them for free.

		To find a tutor for your subject, just make sure you enter it into the search box. 

	@else
		We have a great team of private tutors across {{ $results->titles->location}}. Whether you are looking for a Maths, English or Science Tutor, we have private tutors available across the country for one-to-one tuition in your own home. You can view all of our {{ $results->titles->location }} Tutors’ profiles and message them for free.

		To find a tutor for your subject, just make sure you enter it into the search box. 
	@endif
@endif

@if (!$results->titles->location && !$results->titles->subject))
	Search and book expert, vetted tutors today. Your first lesson is protected by our 100% Money Back Guarantee.
@endif
