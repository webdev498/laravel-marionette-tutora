@extends('_.layouts.default', [
  'page_class' => 'page--student page--messages'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0">@lang('dashboard.student.messages.heading')</h4>
        </div>
    </div>

    @if ( ! Cookie::has('dismissable_student_messages_introduction'))
        <div class="[ band band--ui band--ruled ] [ js-dismissable ]" data-dismissable-id="student_messages_introduction">
            <div class="wrapper">
                <span class="band__close [ icon icon--cancel ] [ js-close ]"></span>
                <p>@lang('dashboard.student.messages.introduction')</p>
            </div>
        </div>
    @endif

    <div class="wrapper u-mt">
        <div class="layout"><!--
            --><div class="layout__item">
                <div class="oxs">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Last message</th>
                                <th>Date received</th>
                                <th>Reply</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($messages->meta->count > 0)
                                @foreach ($messages->data as $message)
                                    <tr class="
                                    @if($message->status->unread)
                                        table__row--unread
                                    @endif">
                                        <td>
                                            <a href="{{ route('tutor.profile.show', ['uuid' => $message->tutor->uuid])}}">
                                                {{ $message->tutor->name }}
                                            </a>
                                        </td>
                                        <td>
                                            @foreach ($message->lines as $line)
                                                <a href="{{ relroute('student.messages.show', [
                                                'uuid' => $message->uuid
                                            ]) }}">{!! $line->short_body !!}</a> 
                                            @endforeach
                                        <td>
                                            <abbr title="{{ $message->time->long }}">{{ $message->time->short }}</abbr>
                                        </td>
                                        <td class="tar">
                                            <a href="{{ relroute('student.messages.show', [
                                                'uuid' => $message->uuid
                                            ]) }}"><span class="icon icon--reply"></span></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        <em>@lang('dashboard.tutor.messages.none')</em>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if ($messages->meta->count > 0)
                    {!! $messages->meta->pagination !!}
                @endif
            </div><!--
        --></div>
    </div>
@stop
