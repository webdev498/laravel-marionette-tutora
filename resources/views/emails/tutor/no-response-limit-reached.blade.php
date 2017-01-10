@extends('emails._.layouts.default')

@section('heading')
    We have taken your profile offline
@stop

@section('body')
    Hey {{ $tutor->first_name }},<br>
    <br>
    We've noticed that you haven't responded to several tuition enquiries. We try to ensure that every enquiry via the website gets a response, and have therefore taken your profile offline for the time being.
    <br>
    <br>
    If you are still looking for tutees, you can go back online by clicking the link below and then clicking "Go Live" on your profile page.
    <br>
    <br>
    The Tutora Team
    
@stop

@section('action')
    <a href="{{ route('tutor.profile.show', [
        'uuid' => $tutor->uuid,
    ]) }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Go Back Online</a>
@stop


