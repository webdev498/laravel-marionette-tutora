@extends('emails._.layouts.default')

@section('heading')
    Your tutor has said they can help!
@stop

@section('body')
    Hey {{ $relationship->student->first_name }},<br>
    <br>
    Your tutor {{ $relationship->tutor->first_name }} has said they are able to help! To respond to their message, just click on the link below and type your response! Here is their message: 
    <br>
    <br>
    <i>{!! str_replace(['</p><p>', '<p>', '</p>'], ['<br><br>', '', ''], $line->getBody()) !!}</i>
    <br>
    <br>
    @if ($line->getLine()->user)
        ~ {{ $line->getLine()->user->first_name }} {{ $line->getLine()->user->last_name }}
    @endif
@stop

@section('action')
    <a href="{{ route('message.redirect', [
        'uuid' => $line->getLine()->message->uuid,
    ]) }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Reply to Message</a>
@stop


