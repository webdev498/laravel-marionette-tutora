@extends('admin.students._.layouts.show')

@section('show')

    @if (count($errors) > 0)
        Errors:
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="layout">
        <div class="[ layout__item ] one-half">
            <form method="post" action="{{ route('admin.students.settings.update', ['uuid' => $student->uuid]) }}" class="[ layout ]">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                Settings
                <div class="[ layout__item ][ field field--label ]">

                    <div class="field__checkbox checkbox {{ $student->settings->receive_requests ? 'checkbox--checked' : '' }}">
                        <div class="checkbox__box">
                            <div class="checkbox__tick"></div>
                            <input type="hidden" name="settings[receive_requests]" value="0">
                            <input type="checkbox" name="settings[receive_requests]" value="1" class="checkbox__input" {{ $student->settings->receive_requests ? 'checked=checked' : '' }}">
                        </div>
                        <label class="checkbox__label">Happy to receive requests from other tutors?</label>
                    </div>
                    <div class="field__error"></div>
                    <div class="field__checkbox checkbox {{ $student->settings->retry_failed_payments? 'checkbox--checked' : '' }}">
                        <div class="checkbox__box">
                            <div class="checkbox__tick"></div>
                            <input type="hidden" name="settings[retry_failed_payments]" value="0">
                            <input type="checkbox" name="settings[retry_failed_payments]" value="1" class="checkbox__input" {{ $student->settings->retry_failed_payments ? 'checked=checked' : '' }}">
                        </div>
                        <label class="checkbox__label">Retry Failed Payments</label>
                    </div>
                    <div class="field__error"></div>
                    
                    <div class="field__checkbox checkbox {{ $student->settings->send_failed_payment_notifications? 'checkbox--checked' : '' }}">
                        <div class="checkbox__box">
                            <div class="checkbox__tick"></div>
                            <input type="hidden" name="settings[send_failed_payment_notifications]" value="0">
                            <input type="checkbox" name="settings[send_failed_payment_notifications]" value="1" class="checkbox__input" {{ $student->settings->send_failed_payment_notifications ? 'checked=checked' : '' }}">
                        </div>
                        <label class="checkbox__label">Send Failed Payment Notifications</label>
                    </div>
                    <div class="field__error"></div>

                    <div class="[ layout__item ] tal u-mt u-mb">
	                    <button class="btn">Save</button>
	                </div>

                </div>
            </form>
        </div>
    </div>

@stop