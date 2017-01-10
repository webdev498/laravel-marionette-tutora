@extends('emails._.layouts.default')

@section('heading')
    Your DBS Check has expired.
@stop

@section('body')
    Hey {{ $user->first_name }},<br>
    <br>
    Your DBS check has expired today. This means that we will no longer display your Check on your profile page. 
    <br>
    <br>
    If you have a more recent DBS check, please upload it by clicking on the link below. We normally review a new DBS check within 2 business days.
@stop

@section('action')
    <a href="{{ route('tutor.profile.show', ['uuid' => $user->uuid]) }}/background_check" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Re-upload a DBS Check</a>
@stop
