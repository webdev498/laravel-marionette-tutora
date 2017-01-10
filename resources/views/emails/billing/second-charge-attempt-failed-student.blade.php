@extends('emails._.layouts.default')

@section('heading')
	
    Payment failed for your lesson yesterday with {{ $tutor->first_name }}
	
@stop

@section('body')
    Hey {{ $student->first_name }},<br/>
    <br/>
    We haven't been able to charge the card you have on file for your lesson with {{ $tutor->first_name }} on {{ $booking->date->short }}.  We will retry the payment shortly. Please get in touch with us on {{ config('contact.phone')}} to arrange payment.
    <br>
    <br>
    If you need to update your credit or debit card, you can re-enter your details by following the link below:
@stop

@section('action')
    <a href="{{ route('student.account.payment.index') }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Re-enter your card</a>
@stop
