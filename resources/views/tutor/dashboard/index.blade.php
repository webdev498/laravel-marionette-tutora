@extends('_.layouts.default', [
    'page_class' => 'page--dashboard page--tutor'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0">@lang('dashboard.tutor.dashboard.heading')</h4>
        </div>
    </div>

    @if ( ! Cookie::has('dismissable_tutor_dashboard_introduction'))
        <div class="[ band band--ui band--ruled ] [ js-dismissable ]" data-dismissable-id="tutor_dashboard_introduction">
            <div class="wrapper">
                <span class="band__close [ icon icon--cancel ] [ js-close ]"></span>
                <p>@lang('dashboard.tutor.dashboard.introduction')</p>
            </div>
        </div>
    @endif

    <div class="wrapper u-mt">
        <div class="layout"><!--
            --><div class="layout__item layout__item--lessons">
                <header class="heading-meta">
                    <h4 class="heading-meta__heading heading">Upcoming Lessons</h4>
                    <a class="heading-meta__meta" href="{{ route('tutor.lessons.index') }}">
                        View All
                    </a>
                </header>

                <div class="oxs">
                    <table class="table">
                        <tbody>
                            @if ($bookings->meta->count > 0)
                                @foreach ($bookings->data as $booking)
                                    <tr class="table__row @if ($booking->is_striked) table__row--strike @endif">
                                        <td>{{ $booking->lesson->relationship->student->name }}</td>
                                        <td>
                                            <abbr title="{{ $booking->date->long }}">{{ $booking->date->short }}</abbr> @ <abbr title="{{ $booking->duration }} in duration">{{ $booking->time->start }} - {{ $booking->time->finish }}</abbr>
                                        </td>
                                        <td class="tar">{{ $booking->status->short }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <td colspan="3"><em>You haven't booked any lessons, yet</em></td>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div><!--

            --><div class="layout__item layout__item--messages">
                <header class="heading-meta">
                    <h4 class="heading-meta__heading heading">Messages</h4>
                    <a class="heading-meta__meta" href="{{ route('tutor.messages.index') }}">
                        View All
                    </a>
                </header>

                @if ($messages->meta->count > 0)
                    <ul class="[ list-ui list-ui--boxes ]">
                        @foreach($messages->data as $message)
                            <li class="list-ui__item">
                                <a href="{{
                                    route('tutor.messages.show', [
                                        'uuid' => $message->uuid
                                    ])
                                }}" class="bare">
                                    <div class="layout"><!--
                                        --><div class="layout__item one-half">
                                    <strong class="list-ui__title brand">
                                        {{ $message->student->name }}
                                    </strong>
                                        </div><!--
                                        --><div class="layout__item one-half tar">
                                            <small>{{ $message->time->short }}</small>
                                        </div><!--
                                        --><div class="layout__item one-whole u-mt--">
                                            @foreach ($message->lines as $line)
                                                {!! $line->short_body !!}
                                            @endforeach
                                        </div><!--
                                    --></div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <em>You haven't received any messages, yet.</em>
                @endif
            </div><!--
        --></div>
    </div>
@stop
