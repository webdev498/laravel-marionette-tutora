@extends('_.layouts.default', [
  'page_class' => 'page--student page--messages'
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
                    <a href="{{ route('tutor.profile.show', ['uuid' => $message->tutor->uuid])}}">
                        {{ $message->tutor->name }}
                        <small>(view profile)</small>
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
            </div><!--

            -->
            <form
                    action="{{ route('student.messages.store', ['uuid' => $message->uuid]) }}"
                    method="post"
                    class="layout__item message-form message-form--active"
            >
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="field {{ $errors->has('body') ? 'field--has-error' : '' }}">
                    <textarea
                            name="body"
                            placeholder="Your message @if(!$message->relationship->is_confirmed)(please don't exchange phone numbers or email addresses)@endif"
                            class="field__input input input--full input--squared input--bordered"
                    >{{ old('body') }}</textarea>
                    <div class="field__error">{{ $errors->first('body') }}</div>
                </div>
                <div class="tar">
                    <button class="btn btn--responsive">Send message</button>
                    <a style="float: left;" href="/faqs#how-do-i-book-and-pay-for-sessions"
                       target="_blank">How do I
                        book and pay for lessons?</a>
                </div>
            </form><!--
        --></div>
    </div>
@stop
