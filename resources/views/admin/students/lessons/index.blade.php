@extends('admin.students._.layouts.show')

@section('show')
<div class="[ band band--ruled band--flush ]">
        <div class="wrapper">
            <div class="[ tabs tabs--full ]">
                <ul class="tabs__list">
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.students.lessons.index', ['uuid' => $student->uuid, 'status' => 'upcoming']) }}" 
                          class="[ tabs__link @if ($status === 'upcoming') tabs__link--active @endif ]"
                        >
                            Upcoming
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.students.lessons.index', ['uuid' => $student->uuid,'status' => 'completed']) }}" 
                          class="[ tabs__link @if ($status === 'completed') tabs__link--active @endif ]"
                        >
                            Completed
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.students.lessons.index', ['uuid' => $student->uuid,'status' => 'cancelled']) }}" d
                          class="[ tabs__link @if ($status === 'cancelled') tabs__link--active @endif ]"
                        >
                            Cancelled
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="layout"><!--
        --><div class="layout__item">
            <div class="oxs">
                <table class="table">
                    <thead>
                        <th>UUID</th>
                        <th>ID</th>
                        <th>Tutor</th>
                        <th>Date / Time</th>
                        <th>Price</th>
                        <th>Subject / Lesson</th>
                        <th>Statuses</th>
                        <th>Relationship</th>
                        <th>&nbsp;</th>
                    </thead>
                    <tbody>
                        @foreach ($bookings->data as $booking)
                            <tr>
                                <!-- UUID -->
                                <td>{{ $booking->uuid }}</td>
                                <!-- UUID -->
                                <td>{{ $booking->id }}</td>
                                <!-- Tutor -->
                                <td>
                                    <a href="{{ route('admin.tutors.show', [
                                        'uuid' => $booking->lesson->relationship->tutor->uuid
                                    ]) }}">
                                        {{ $booking->lesson->relationship->tutor->private->name }}
                                    </a><br>
                                    {{ $booking->lesson->relationship->tutor->uuid }}
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
                                <!-- Subject -->
                                <td>
                                    {{ $booking->lesson->subject->title }}<br>
                                    <em>@ {{ $booking->location }}</em>
                                </td>
                                <!-- Statuses -->
                                <td>
                                    {{ $booking->status->admin->status }}<br>
                                    {{ $booking->status->admin->charge_status }} <br>
                                    {{ $booking->status->admin->transfer_status }}
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
                                    @if ($booking->actions->confirm === true)
                                        <a href="{{ route('admin.students.lessons.confirm', [
                                            'student' => $student->uuid,
                                            'booking' => $booking->uuid,
                                        ]) }}">
                                            <span title="Confirm booking" class="icon icon--confirm"></span>
                                        </a>
                                    @endif
                                    @if ($booking->actions->retry === true)
                                        <a href="{{ route('admin.students.lessons.retry', [
                                            'student' => $student->uuid,
                                            'booking' => $booking->uuid,

                                        ]) }}">
                                            <span title="Retry booking" class="btn btn--small">Retry</span>
                                        </a>
                                    @endif
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
@stop
