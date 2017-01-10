@extends('emails._.layouts.default')

@section('heading')
    We had a problem transfering payments to your bank
@stop

@section('body')
    Hey {{ $tutor->first_name }},<br>
    <br>
    We had a problem transferring payments for your recent lesson to your chosen bank account. Please ensure that the bank account details you have provided are correct by logging into your account.
    <br>
    <br>
    We will try to transfer the funds again in a few days. If you are still having problems, please get in touch with us through the Contact Us form on the website.
@stop

@section('action')
    <a href="{{ route('tutor.account.payment.index') }}" class="btn__anchor" style="color:#ffffff !important; text-decoration: none !important;">View My Payment Settings</a>
@stop
