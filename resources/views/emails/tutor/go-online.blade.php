@extends('emails._.layouts.default')

@section('heading')
    Go online and start tutoring again? 
@stop

@section('body')
    Hey {{ $tutor->first_name }},<br>
    <br>
    It's been a while since you took your profile offline, and we wanted to check in and see if you are interested in tutoring again?
    <br>
    <br>
    To start receiving tuition requests, simply click the link below and put your profile back online. 
    <br>
    <br>
    Kind Regards,
    <br>
    The Tutora Team
    <br>
@stop

@section('action')
    <a href="{{ route('tutor.profile.show', [
        'uuid' => $tutor->uuid,
        'section' => 'go-live'
    ]) }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Go Back Online</a>
@stop
