@extends('emails._.layouts.default')

@section('heading')
    Lesson cancelled with {{ $relationship->tutor->first_name }}
@stop

@section('body')
    Hey {{ $relationship->student->first_name }},<br>
    <br>
    As you have not accepted the booking request from {{ $relationship->tutor->first_name }} in time, your upcoming lesson has been cancelled.<br>
    <br>
    If you still wish to receive a lesson, please ask your tutor to reschedule.<br>
@stop

@section('action')
    <a href="{{ route('student.dashboard.index') }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Go to your dashboard</a>
@stop
