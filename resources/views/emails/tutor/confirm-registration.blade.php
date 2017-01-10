@extends('emails._.layouts.default')

@section('heading')
    Thank you for joining Tutora.
@stop

@section('body')
   Please click on the link below to complete your signup.  
@stop

@section('action')
    <a href="{{ route('register.confirm', [
        'uuid'  => $user->uuid,
        'token' => $user->confirmation_token,
    ]) }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Complete Registration</a>
@stop
