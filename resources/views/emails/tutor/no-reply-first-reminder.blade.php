@extends('emails._.layouts.default')

@section('heading')
    Please reply to your enquiry from {{ $relationship->student->first_name }}
@stop

@section('body')
    Hey {{ $relationship->tutor->first_name }},<br>
    <br>
    Your enquiry from {{ $relationship->student->first_name }} is still awaiting a reply. Please remember that your position in our search results is affected by the speed of your responses to enquiries.  Here's their enquiry:
    <br>
    <br>
     {!! str_replace(['</p><p>', '<p>', '</p>'], ['<br><br>', '', ''], $line->getBody()) !!}<br>
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


