@extends('_.layouts.default', [
  'page_class' => 'page--student page--lessons',
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0">@lang('dashboard.student.lessons.heading')</h4>
        </div>
    </div>
   

    @if ( ! Cookie::has('dismissable_student_lessons_introduction'))
        <div class="[ band band--ui band--ruled ] [ js-dismissable ]" data-dismissable-id="student_lessons_introduction">
            <div class="wrapper">
                <span class="band__close [ icon icon--cancel ] [ js-close ]"></span>
                <p>@lang('dashboard.student.lessons.introduction')</p>
            </div>
        </div>
    @endif
    <div class="[ band band--ruled band--flush ]">
        <div class="wrapper">
            <div class="[ tabs tabs--full ]">
                <ul class="tabs__list">
                    <li class="tabs__item">
                        <a href="{{ relroute('student.lessons.index', ['status' => 'upcoming']) }}" 
                          class="[ tabs__link @if ($status === 'upcoming') tabs__link--active @endif ]"
                        >
                            Upcoming
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a href="{{ relroute('student.lessons.index', ['status' => 'completed']) }}" 
                          class="[ tabs__link @if ($status === 'completed') tabs__link--active @endif ]"
                        >
                            Completed
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a href="{{ relroute('student.lessons.index', ['status' => 'cancelled']) }}" d
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
                                <th>Tutor</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($bookings->meta->count > 0)
                                @foreach ($bookings->data as $booking)
                                    <tr class="
                                        table__row
                                        @if ($booking->actions->pay === true) table__row--warn @endif
                                    ">
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
                                        <td>
                                            <a href="{{ route('tutor.profile.show', ['uuid' => $booking->lesson->relationship->tutor->uuid])}}">
                                                {{ $booking->lesson->relationship->tutor->name }}
                                            </a>
                                        </td>
                                        <td>{{ $booking->lesson->subject->title }}</td>
                                        <td>
                                            <abbr title="{{ $booking->status->student->long }}">
                                                {{ $booking->status->student->short }}
                                            </abbr>
                                        </td>
                                        <td>
                                            <div class="tar">
                                                @if ($booking->actions->confirm === true)
                                                    <a href="{{ route('student.lessons.confirm', [
                                                        'booking' => $booking->uuid
                                                    ]) }}">
                                                        <span title="Confirm booking" class="icon icon--confirm"></span>
                                                    </a>
                                                @endif

                                                @if ($booking->actions->pay === true)
                                                    <a href="{{ relroute('student.lessons.pay', [
                                                        'booking' => $booking->uuid
                                                    ]) }}" data-js>
                                                        <span title="Retry payment" class="icon icon--card"></span>
                                                    </a>
                                                @endif

                                                @if ($booking->actions->cancel === true)
                                                    <a href="{{ relroute('student.lessons.cancel', [
                                                        'booking' => $booking->uuid
                                                    ]) }}" data-js>
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
                                        <em>@lang('dashboard.student.lessons.none')</em>
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
