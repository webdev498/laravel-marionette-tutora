@extends('admin.relationships._.layouts.show')

@section('show')
    <div class="wrapper">
        <div class="layout"><!--
            --><div class="layout__item">
                <div class="messages messages--bare">
                    <ul class="layout messages__list"><!--
                        @foreach ($message->lines as $line)
                            --><li class="layout__item messages__item messages__item--{{ $line->who }}">
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

            --><form
                                     action="{{ route('admin.relationships.messages.store', ['id' => $message->relationship->id]) }}"
              method="post"
              class="layout__item message-form message-form--active"
            >
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="field">
                    <textarea name="body" placeholder="Message"
                      class="field__input input input--full input--squared input--bordered"></textarea>
                    <div class="field__error"></div>
                </div>

                <div class="tar">
                    <button class="btn">Send message</button>
                </div>
            </form><!--
        --></div>
    </div>
@stop
