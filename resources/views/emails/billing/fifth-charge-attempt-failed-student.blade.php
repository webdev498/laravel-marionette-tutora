@extends('emails._.layouts.default')

@section('heading')
	
    URGENT: Unpaid Lesson With {{ $tutor->first_name }} on {{ $booking->date->long }}
	
@stop

@section('body')
    Hi {{ $student->first_name }},<br/>
    <br/>
    Your lesson {{ $booking->date->forHumans }} with {{ $tutor->first_name }} still hasn't been paid for. We will attempt payment again shortly. If we have not been able to collect payment, we normally pass the debt onto a debt collection agency.
    <br>
    <br>
    Please ring us on 0114 383 0989 immediately to arrange payment. Should you need to update your card for us to collect payment, you can do so using the link below: 
@stop

@section('action')
    <a href="{{ route('student.account.payment.index') }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Re-enter your card</a>
@stop
