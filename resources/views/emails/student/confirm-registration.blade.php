@extends('emails._.layouts.default')

@section('heading')
    Welcome to Tutora!
@stop

@section('body')
    Hey {{ $user->first_name }},<br>
    <br>
    Thanks for getting in touch with one of our tutors, who will get back to you soon. Here's how it works...
    <br>
    <br>
    1) Message tutors (we suggest getting in touch with a couple) to find the right person for you, and to agree the best time and location for tuition.
    <br>
    2) Once you've agreed a time and location, ask them to book a session in for you. Payment is via credit or debit card. Sorry - we can’t accept cash payment.
    <br>
    <br>
    Don’t forget that you don’t pay until after each session and there is no minimum number of sessions.
    <br>
    <br>
    If you have any questions, feel free to email us on support@tutora.co.uk. We are always happy to help.
    <br>
    <br>
    

@stop

@section('action')
    <a href="{{ route('register.confirm', [
        'uuid'  => $user->uuid,
        'token' => $user->confirmation_token,
    ]) }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Complete Registration</a>
@stop
