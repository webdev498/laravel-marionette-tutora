@extends('emails._.layouts.default')

@section('heading')
    There was a problem with your identification
@stop

@section('body')
    Hey {{ $user->first_name }},<br>
    <br>
    There was a problem verifying your identification. The error given was:<br>
    <br>
    "{{ $error }}"<br>
    <br>
    If you have any problems re-uploading your identification, please give us a call on 01143830989 or simply reply to this email.
@stop

@section('action')
    <a href="{{ route('tutor.account.identification.index') }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Re-upload your identification</a>
@stop
