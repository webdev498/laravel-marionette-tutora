@extends('emails._.layouts.default')

@section('heading')
     Please leave a review for your lesson with {{ $reviewee->first_name }}
@stop

@section('body')
    Hey {{ $reviewer->first_name }},<br>
    <br>
    We hope your recent lesson with {{ $reviewee->first_name }} went well.  We would really value your feedback, so could you please leave a review for your lesson.
@stop

@section('action')
    <a href="{{ route('review.create', [
        'tutor' => $reviewee->uuid
    ]) }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Leave a review</a>
@stop
