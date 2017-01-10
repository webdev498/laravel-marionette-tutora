@extends('_.layouts.default', [
    'page_class' => 'page--lessons page--admin'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav.authed.admin')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0">Lessons</h4>
        </div>
    </div>

    <div class="[ band band--ruled band--flush ]">
        <div class="wrapper">
            <div class="[ tabs tabs--full ]">
                <ul class="tabs__list">
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.lessons.index') }}"
                          class="[ tabs__link @if ( ! $status) tabs__link--active @endif ]"
                        >
                            All
                        </a>
                    </li>
                    @foreach([
                        \App\LessonBooking::PENDING   => 'Pending',
                        \App\LessonBooking::CONFIRMED => 'Confirmed',
                        \App\LessonBooking::COMPLETED => 'Completed',
                        \App\LessonBooking::CANCELLED => 'Cancelled',
                    ] as $key => $value)
                        <li class="tabs__item">
                            <a
                                href="{{ relroute('admin.lessons.index', ['status' => $key]) }}"
                                class="[ tabs__link @if ($status === $key) tabs__link--active @endif ]"
                            >
                                {{ $value }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="[ band band--ruled band--flush ]">
        <div class="[ tabs tabs--full ]">
            <ul class="tabs__list">
                <li class="tabs__item">
                    <a href="{{ relroute('admin.lessons.index', ['status' => $status]) }}"
                        class="[ tabs__link @if ( ! $chargeStatus) tabs__link--active @endif ]"
                    >
                        All
                    </a>
                </li>
                @foreach([
                    \App\LessonBooking::PENDING               => 'Pending',
                    \App\LessonBooking::AUTHORISED            => 'Authed',
                    \App\LessonBooking::AUTHORISATION_PENDING => 'Auth Pending',
                    \App\LessonBooking::AUTHORISATION_FAILED  => 'Auth Failed',
                    \App\LessonBooking::PAID                  => 'Paid',
                    \App\LessonBooking::PAYMENT_PENDING       => 'Payment Pending',
                    \App\LessonBooking::PAYMENT_FAILED        => 'Payment Failed',
                    \App\LessonBooking::REFUNDED              => 'Refunded',
                ] as $key => $value)
                    <li class="tabs__item">
                        <a 
                            href="{{ relroute('admin.lessons.index', ['status' => $status, 'chargeStatus' => $key]) }}"
                            class="[ tabs__link @if ($chargeStatus === $key) tabs__link--active @endif ]"
                        >
                            {{ $value }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="wrapper u-mt">
        <div class="layout"><!--
            --><div class="layout__item">
                <div class="oxs">
                    <table class="table">
                        <thead>
                            <th>UUID</th>
                            <th>Tutor</th>
                            <th>Student</th>
                            <th>Date / Time</th>
                            <th>Price</th>
                            <th>Subject / Location</th>
                            <th>Statuses</th>
                            <th>Relationship</th>
                            <th>&nbsp;</th>
                        </thead>
                        <tbody>
                            @foreach ($bookings->data as $booking)
                                <tr>
                                    <!-- UUID -->
                                    <td>{{ $booking->uuid }}</td>
                                    <!-- Tutor -->
                                    <td>
                                        <a href="{{ route('admin.tutors.show', [
                                            'uuid' => $booking->lesson->relationship->tutor->uuid
                                        ]) }}">
                                            {{ $booking->lesson->relationship->tutor->private->name }}
                                        </a><br>
                                        {{ $booking->lesson->relationship->tutor->uuid }}
                                    </td>

                                    <!-- Student -->
                                    <td>
                                        <a href="{{ route('admin.students.show', [
                                            'uuid' => $booking->lesson->relationship->student->uuid
                                        ]) }}">
                                            {{ $booking->lesson->relationship->student->private->name }}
                                        </a><br>
                                        {{ $booking->lesson->relationship->student->uuid }}
                                    </td>
                                    <!-- Date -->
                                    <td>
                                        <abbr title="{{ $booking->date->long }}">
                                            {{ $booking->date->short }}
                                        </abbr><br>
                                        <abbr title="{{ $booking->duration }} in duration">
                                            {{ $booking->time->start }} - {{ $booking->time->finish }}
                                        </abbr>
                                    </td>
                                    <!-- Price -->
                                    <td>
                                        <abbr title="Based on a rate of {{ $booking->rate }} per hour.">
                                            {{ $booking->price }}
                                        </abbr>
                                    </td>
                                    <!-- Subject / Location-->
                                    <td>
                                        {{ $booking->lesson->subject->title }}<br>
                                        <em>@ {{ $booking->location }}</em>
                                    </td>
                                    <!-- Statuses -->
                                    <td>
                                        {{ $booking->status->admin->status }}<br>
                                        {{ $booking->status->admin->charge_status }} <br>
                                        {{ $booking->status->admin->transfer_status->value }}
                                    </td>
                                    <!-- Relationship -->
                                    <td class="u-vam">
                                        <a href="{{ route('admin.relationships.show', [
                                            'id' => $booking->lesson->relationship->id,
                                        ]) }}">
                                            <span title="Show relationship" class="icon icon--eye"></span>
                                        </a>
                                    </td>

                                    <!-- Edit -->
                                    <td class="u-vam tar">
                                        @if ($booking->actions->edit === true)
                                        <a href="{{ route('admin.lessons.edit', [
                                            'booking' => $booking->uuid
                                        ]) }}">
                                            <span title="Edit booking" class="icon icon--edit"></span>
                                        </a>
                                    @endif
                                    @if ($booking->actions->cancel === true)
                                        <a href="{{ relroute('admin.lessons.cancel', [
                                            'booking' => $booking->uuid
                                        ]) }}" data-js>
                                            <span title="Cancel booking" class="icon icon--delete"></span>
                                        </a>
                                    @endif
                                    @if ($booking->actions->refund === true)
                                        <a href="{{ relroute('admin.lessons.refund', [
                                            'booking' => $booking->uuid
                                        ]) }}">
                                            <span title="Refund booking" class="icon icon--card"></span>
                                        </a>
                                    @endif
                                    </td>
                                </tr>
                            @endforeach
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
