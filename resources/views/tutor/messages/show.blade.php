@extends('_.layouts.default', [
  'page_class' => 'page--tutor page--messages'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>

    <div class="wrapper">
        <div class="layout"><!--
            -->
            <div class="layout__item message-head u-mt u-mb--">
                <h3 class="delta">
                    {{ $message->student->name }}
                    @if(count($message->searches) !== 0)
                        - @if ($message->searches[0]->subject){{$message->searches[0]->subject->title}}
                        , @endif {{$message->searches[0]->location}}
                    @endif

                    <a id="btn-book-trial-lesson"
                       href="{{ relroute('tutor.lessons.create', ['student' => $message->student->uuid, 'trial' => 1]) }}" data-js
                       class="r btn btn--responsive btn--hollow btn--dark btn--squared btn--sm-50-percent">Book a trial
                    </a>
                    <a id="btn-book-lesson"
                       href="{{ relroute('tutor.lessons.create', ['student' => $message->student->uuid]) }}" data-js
                       class="r btn btn--responsive btn--hollow btn--squared u-mr btn--sm-50-percent">Book a lesson
                    </a>
                </h3>
            </div><!--
    
            -->
            <div class="layout__item">
                <div class="messages">
                    <ul class="layout messages__list"><!--
                        @foreach ($message->lines as $line)
                        -->
                            <li class="layout__item messages__item messages__item--{{ $line->who }}">
                                <div class="messages__timestamp">
                                    <abbr title="{{ $line->time->long }}">
                                        {{ $line->time->short }}
                                    </abbr>
                                </div>
                                <div class="messages__body">
                                    {!! $line->body !!}
                                </div>
                            </li><!--
                        @endforeach
                            --></ul>
                </div>
            </div>
            @if (! $message->hasReply || $message->relationship->status == \App\Relationship::MISMATCHED)
                <div class="layout__item tutor-intent tutor-intent--active">
                    Will you be able to help this Student?
                    <div>
                        <button class="btn btn--responsive btn--success help-yes">Yes, I can help</button>
                        <button class="btn btn--responsive btn--error help-no">Sorry, I can't help at this time</button>
                    </div>
                    <a style="text-align: center; display: block; padding-top: 1rem;"
                       href="/faqs/for-tutors#how-do-i-book-and-get-paid-for-lessons"
                       target="_blank">How do I book and get paid for
                        lessons?</a>
                </div>
            @endif
            <form
                    action="{{ route('tutor.messages.store', ['uuid' => $message->uuid]) }}"
                    method="post"
                    class="layout__item message-form @if ( $message->hasReply && $message->relationship->status !== \App\Relationship::MISMATCHED) message-form--active @endif"
            >
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="intent" id="intent" value="1">
                <input type="hidden" name="reason" id="reason" value="">
                <div id="message" class="field {{ $errors->has('body') ? 'field--has-error' : '' }} can-help">
                    <div class="popover"><span class="message">We have pre-filled in a message for you, but please feel free to amend it as you wish.</span>
                    </div>
                    <textarea name="body"
                              placeholder="Your message @if(!$message->relationship->is_confirmed)(please don't exchange phone numbers or email addresses)@endif"
                              class="field__input input input--full input--squared input--bordered">{{ old('body') }}</textarea>
                    <div class="field__error">{{ $errors->first('body') }}</div>
                </div>
                <div class="tar">
                    <button type="button" class="u-mr btn btn--error btn--responsive help-no change-reason"
                            @if ($message->relationship->status !== \App\Relationship::MISMATCHED) style="display:none" @endif>
                        Change the reason
                    </button>
                    <button type="button" class="u-mr btn btn--success btn--responsive help-yes"
                            @if ($message->relationship->status !== \App\Relationship::MISMATCHED) style="display:none" @endif>
                        I've changed my mind, I can help
                    </button>
                    <button type="button" class="u-mr btn btn--error btn--responsive help-no"
                            @if ($message->relationship->status == \App\Relationship::MISMATCHED) style="display:none" @endif>
                        I can't help
                    </button>
                    <button type="submit" class="btn btn--responsive">Send message</button>
                    <a style="float: left"
                       href="/faqs/for-tutors#how-do-i-book-and-get-paid-for-lessons"
                       target="_blank">How do I book and get paid for
                        lessons?</a>
                </div>
            </form>
        </div>
    </div>

    @if ($dialogue = session('dialogue'))
        <div id="dialogue" class="dialogue dialogue--open">
            <div class="dialogue__wrapper wrapper">
                <div class="layout layout--center">
                    <div class="layout__item dialogue__container">
                        <div class="dialogue__window">
                            <header class="dialogue__header">
                                <h4 class="heading">Thank you for letting us know</h4>
                                <a href="#" class="close icon icon--cross [ js-close ]"></a>
                            </header>
                            <p>
                                {{ $dialogue['message'] }}
                            </p>
                            @if($dialogue['type'] == 'profile_offline')
                                <div class="tar">
                                    <button class="btn btn--responsive btn--error go-offline">Go offline</button>
                                    <a href="#" class="btn [ js-close ]">No, I'll stay online for now.</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="dialogue__overlay [ js-close ]"></div>
        </div>
    @endif

    <div id="reason-dialogue" class="dialogue">
        <div class="dialogue__wrapper wrapper">
            <div class="layout layout--center">
                <div class="layout__item dialogue__container">
                    <div class="dialogue__window">
                        <header class="dialogue__header">
                            <h4 class="heading">Sorry, I can't help this Student</h4>
                            <a href="#" class="close icon icon--cross [ js-close ]"></a>
                        </header>
                        <p>
                            Please click on the reason from the list below, this will allow us to help the student
                            further since you have said that you cannot help.
                        </p>
                        <div class="field c">
                            <label class="field__label"></label>
                            <div class="radios">
                                <div class="radios__item">
                                    Sorry, I don’t have any availability at the moment
                                    <input type="radio" name="reason_select" value="not_available"
                                           class="radios__input reason"
                                           data-message="Hi {{ $message->student->name }}, thanks for your message. Sorry, I’d love to help but I don’t have any availability at the moment. Please do another search and message another tutor. I’m sure Tutora will be in touch to help you find someone else. Thanks, {{$user->first_name}}">
                                </div>
                                <div class="radios__item">
                                    Sorry, you live too far from me.
                                    <input type="radio" name="reason_select" value="distance"
                                           class="radios__input reason"
                                           data-message="Hi {{ $message->student->name }}, thanks for your message. Sorry, I’d love to help but you just live too far from me. Please do another search and message another tutor. I’m sure Tutora will be in touch to help you find someone else. Thanks, {{$user->first_name}}">
                                </div>
                                <div class="radios__item">
                                    Sorry, I don’t teach that subject at that level
                                    <input type="radio" name="reason_select" value="wrong_level"
                                           class="radios__input reason"
                                           data-message="Hi {{ $message->student->name }}, thanks for your message. Sorry, I’d love to help but you I don’t tutor that subject at that level. Please do another search and message another tutor. I’m sure Tutora will be in touch to help you find someone else. Thanks, {{$user->first_name}}">
                                </div>
                                <div class="radios__item">
                                    Sorry, I am not tutoring any more
                                    <input type="radio" name="reason_select" value="dont_tutor"
                                           class="radios__input reason"
                                           data-message="Hi {{ $message->student->name }}, thanks for your message. Sorry, I’d love to help but you I am no longer tutoring any more. Please do another search and message another tutor. I’m sure Tutora will be in touch to help you find someone else. Thanks, {{$user->first_name}}">
                                </div>
                                <div class="radios__item">
                                    Other Reason
                                    <input type="radio" name="reason_select" value="other" class="radios__input reason"
                                           data-message="">
                                </div>
                            </div>
                            <div class="field__error"></div>
                        </div>
                        <button class="btn r btn--responsive btn--disabled btn-reason" disabled>Submit</button>
                        <div class="c"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dialogue__overlay [ js-close ]"></div>
    </div>
@stop
