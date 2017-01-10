@extends('emails._.layouts.default')

@section('heading')
	
    FINAL NOTICE: Outstanding payment for your lesson on {{ $booking->date->long }}
	
@stop

@section('body')
    Hey {{ $student->first_name }},<br/>
    <br/>
    It's been {{ $booking->date->forHumansShort }} since your lesson with {{ $tutor->first_name }} and we've not been able to collect payment.  Could you please contact us on {{ config('contact.phone')}} to arrange payment.  If we are not able to collect payment shortly, we may stop any future lessons taking place.
    <br>
    <br>
    If you want to update your payment details online, please do so using the link below:
@stop

@section('action')
    <a href="{{ route('student.account.payment.index') }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Re-enter your card</a>
@stop
