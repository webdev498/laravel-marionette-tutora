@extends('emails._.layouts.default')

@section('heading')
    Please reply to your enquiry from {{ $relationship->student->first_name }}
@stop

@section('body')
    Hey {{ $relationship->tutor->first_name }},<br>
    <br>
    It's been 5 days and you still haven't replied to an enquiry from {{ $relationship->student->first_name }}. Please reply to every student who contacts you, even if you cannot help them. We aim to help every student who visits our site, so it is important that everyone helps work towards that goal. <br>
    <br>
    Please reply to {{ $relationship->student->first_name }} by going to your dashboard and click. If you do not have space for more students at the moment, you can always take your profile offline: 
    <br>
@stop

@section('action')
    <a href="{{ route('message.redirect', [
        'uuid' => $line->getLine()->message->uuid,
    ]) }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Reply to Message</a> or  
    <a href="{{ route('message.redirect', [
        'uuid' => $line->getLine()->message->uuid,
    ]) }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Go Offline</a>
@stop


