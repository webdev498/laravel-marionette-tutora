@extends('emails._.layouts.default')

@section('heading')
    Your DBS Check exprired two weeks ago.
@stop

@section('body')
    Hey {{ $user->first_name }},<br>
    <br>
    Your background check expired two weeks ago. If you wish to upload another DBS check, please do so by clicking on the link below. We normally review DBS checks within 2 business days of receiving them. 
@stop

@section('action')
    <a href="{{ route('tutor.profile.show', ['uuid' => $user->uuid]) }}/background_check" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Re-upload a DBS Check</a>
@stop
