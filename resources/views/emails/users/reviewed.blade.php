@extends('emails._.layouts.default')

@section('heading')
    Lesson review left by {{ $review->reviewer->first_name }}
@stop

@section('body')
    Hey {{ $review->user->first_name }},<br>
    <br>
    {{ $review->reviewer->first_name }} has left a review for you!
@stop

@section('action')
    <a href="{{ route('tutor.dashboard.index') }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Go to your dashboard</a>
@stop
