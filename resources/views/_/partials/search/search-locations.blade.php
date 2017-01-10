
@if($locations)
	<h4>Tutora also offers tuition in:</h4>

	@foreach($locations as $location )

		@if (! $results->titles->location)
		
			<a href="{{ route('search.index', [lcfirst($results->titles->subject), location_to_url($location)]) }}">{{ str_unslug($location) }}</a>,

		@else
			<a href="{{ route('search.location', [location_to_url($location)]) }}">{{ location_to_str($location) }}</a>,
		@endif
	@endforeach
@endif