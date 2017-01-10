@extends('emails._.layouts.default')

@section('heading')
    Your identification has been verified!
@stop

@section('body')
    Hey {{ $user->first_name }},<br>
    <br>
    Your identification has been verified!
@stop

@section('action')
    <a href="{{ route('tutor.dashboard.index') }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Go to your dashboard</a>
@stop
