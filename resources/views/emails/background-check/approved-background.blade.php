@extends('emails._.layouts.default')

@section('heading')
    Your DBS was approved!
@stop

@section('body')
    Hey {{ $user->first_name }},<br>
    <br>
    Your DBS check was approved! This means we will display the DBS checked status on your profile for potential clients to see. 
@stop

@section('action')
    <a href="{{ route('tutor.dashboard.index') }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Go to your dashboard</a>
@stop
