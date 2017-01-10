@extends('emails._.layouts.default')

@section('heading')
	
    A reminder: Please ensure there are funds in place for your lesson on {{ $booking->date->long }}
	
@stop

@section('body')
    Hey {{ $student->first_name }},<br/>
    <br/>
    This is a reminder to make sure there are funds available for your upcoming lesson with {{ $tutor->first_name }} (on {{ $booking->date->short }}).  Could you please ensure there are sufficient funds in place prior to your lesson starting. 
    <br>
    <br>
    If you need to update your credit or debit card, you can re-enter your details by following the link below:
@stop

@section('action')
    <a href="{{ route('student.account.payment.index') }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Re-enter your card</a>
@stop
