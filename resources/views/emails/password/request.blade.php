@extends('emails._.layouts.default')

@section('heading')
    Lost Password
@stop

@section('body')
    Hey {{ $user->first_name }},<br>
    <br>
    We heard that you lost your Tutora password. Sorry about that!<br>
    <br>
    But don't worry. You can use the following link within the next 24 hours to reset your password:
@stop

@section('action')
    <a href="{{ route('password.edit', ['token' => $token]) }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Reset your password</a>
@stop
