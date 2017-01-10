@extends('_.layouts.default', [
  'page_class' => 'page--tutor page--lessons',
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0">@lang('dashboard.tutor.lessons.heading')</h4>
        </div>
    </div>

    @if ( ! Cookie::has('dismissable_tutor_lessons_introduction'))
        <div class="[ band band--ui band--ruled ] [ js-dismissable ]" data-dismissable-id="tutor_lessons_introduction">
            <div class="wrapper">
                <span class="band__close [ icon icon--cancel ] [ js-close ]"></span>
                <p>@lang('dashboard.tutor.lessons.introduction')</p>
            </div>
        </div>
    @endif

    <div class="[ band band--ruled band--flush ]">
        <div class="wrapper">
            <div class="[ tabs tabs--full ]">
                <ul class="tabs__list">
                    <li class="tabs__item">
                        <a href="{{ relroute('tutor.lessons.index', ['status' => 'upcoming']) }}" 
                          class="[ tabs__link @if ($status === 'upcoming') tabs__link--active @endif ]"
                        >
                            Upcoming
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a href="{{ relroute('tutor.lessons.index', ['status' => 'completed']) }}" 
                          class="[ tabs__link @if ($status === 'completed') tabs__link--active @endif ]"
                        >
                            Completed
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a href="{{ relroute('tutor.lessons.index', ['status' => 'cancelled']) }}" d
                          class="[ tabs__link @if ($status === 'cancelled') tabs__link--active @endif ]"
                        >
                            Cancelled
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="wrapper u-mt">
        <div class="layout"><!--
            --><div class="layout__item">
                <div class="oxs">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Price</th>
                                <th>Student</th>
                                <th>Subject</th>
                                <th>Status</th>
                                @if ($status === 'completed') <th>Payment Status</th> @endif
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($bookings->meta->count > 0)
                                @foreach ($bookings->data as $booking)
                                    <tr class="table__row">
                                        <td>
                                            <abbr title="{{ $booking->date->long }}">
                                                {{ $booking->date->short }}
                                            </abbr>
                                        </td>
                                        <td>
                                            <abbr title="{{ $booking->duration }} in duration">
                                                {{ $booking->time->start }} - {{ $booking->time->finish }}
                                            </abbr>
                                        </td>
                                        <td>
                                            <abbr title="Based on a rate of {{ $booking->rate }} per hour.">
                                                {{ $booking->price }}
                                            </abbr>
                                        </td>
                                        <td>{{ $booking->lesson->relationship->student->name }}</td>
                                        <td>{{ $booking->lesson->subject->title }}</td>
                                        <td>
                                            <abbr title="{{ $booking->status->tutor->long }}">
                                                {{ $booking->status->tutor->status->short }}
                                            </abbr>
                                        </td>
                                        @if ($status === 'completed')
                                            <td class="{{ $booking->status->tutor->transfer_status->class }}">
                                                {{ $booking->status->tutor->transfer_status->value }}
                                            </td>
                                        @endif
                                        <td>
                                            <div class="tar">
                                                @if ($booking->actions->edit === true)
                                                    <a href="{{ relroute('tutor.lessons.edit', ['booking' => $booking->uuid]) }}" data-js>
                                                        <span title="Edit booking" class="icon icon--edit"></span>
                                                    </a>
                                                @endif
                                                @if ($booking->actions->cancel === true)
                                                    <a href="{{ relroute('tutor.lessons.cancel', ['booking' => $booking->uuid]) }}" data-js>
                                                        <span title="Cancel booking" class="icon icon--delete"></span>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">
                                        <em>@lang('dashboard.tutor.lessons.none')</em>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if ($bookings->meta->count > 0)
                    {!! $bookings->meta->pagination !!}
                @endif
            </div><!--
        --></div>
    </div>
@stop
