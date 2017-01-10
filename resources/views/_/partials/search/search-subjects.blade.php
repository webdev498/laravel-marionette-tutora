
@if($subjects)
	<h4>Subjects Offered In {{$results->titles->location }}</h4>
	@foreach($subjects as $subject => $value)
		<a href="{{ route('search.index', [$subject, location_to_url($results->titles->location)]) }}">{{ str_unslug($subject) }}</a>,
	@endforeach

@endif