@extends('emails._.layouts.default')

@section('heading')
	
    Payment outstanding for your lesson with {{ $tutor->first_name }}
	
@stop

@section('body')
    Hey {{ $student->first_name }},<br/>
    <br/>
    We haven't been able to charge your card for your lesson with {{ $tutor->first_name }} for {{ $booking->price }}. We will retry the payment tomorrow. We do need to collect payment before any further lessons go ahead, so could you please make sure there are sufficient funds in your bank account. 
    <br>
    <br>
    If you need to update your credit or debit card, you can re-enter your details by following the link below:
@stop

@section('action')
    <a href="{{ route('student.account.payment.index') }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">Re-enter your card</a>
@stop
