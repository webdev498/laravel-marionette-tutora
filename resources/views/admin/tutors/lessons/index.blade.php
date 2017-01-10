@extends('admin.tutors._.layouts.edit')

@section('show')

<div class="[ band--bottom ]">
    <div class="wrapper">
        <a class="[ btn btn--small ] u--mt--" data-js="" href="{{ relroute('admin.tutors.lessons.create', ['uuid' => $tutor->uuid]) }}">
            Book A Lesson
        </a>

        <a class="[ btn btn--small ] u--mt--" data-js="" href="{{ relroute('admin.tutors.lessons.create', ['uuid' => $tutor->uuid, 'trial' => 1]) }}">
            Book A Trial Lesson
        </a>
    </div>
</div>
<div class="[ band band--ruled band--flush ]">
        <div class="wrapper">
            <div class="[ tabs tabs--full ]">
                <ul class="tabs__list">
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.tutors.lessons.index', ['uuid' => $tutor->uuid, 'status' => 'upcoming']) }}" 
                          class="[ tabs__link @if ($status === 'upcoming') tabs__link--active @endif ]"
                        >
                            Upcoming
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.tutors.lessons.index', ['uuid' => $tutor->uuid,'status' => 'completed']) }}" 
                          class="[ tabs__link @if ($status === 'completed') tabs__link--active @endif ]"
                        >
                            Completed
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.tutors.lessons.index', ['uuid' => $tutor->uuid,'status' => 'cancelled']) }}" d
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
                        <th>Student</th>
                        <th>Date / Time</th>
                        <th>Price</th>
                        <th>Subject / Location</th>
                        <th>Statuses</th>
                        @if ($status === 'completed') <th>Transfer Status</th> @endif
                        <th>Relationship</th>

                        <th>&nbsp;</th>
                    </thead>
                    <tbody>
                        @foreach ($bookings->data as $booking)
                            <tr>
                                <!-- UUID -->
                                <td>{{ $booking->uuid }}</td>
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
                                <!-- Subject / Location -->
                                <td>
                                    {{ $booking->lesson->subject->title }}<br>
                                    <em>@ {{ $booking->location }}</em>
                                </td>
                                <!-- Statuses -->
                                <td>
                                    {{ $booking->status->admin->status }}<br>
                                    {{ $booking->status->admin->charge_status }} <br>
                                    
                                </td>
                                @if ($status === 'completed')
                                    <td class="{{ $booking->status->admin->transfer_status->class }}">
                                        {{ $booking->status->admin->transfer_status->value }}
                                    </td>
                                @endif
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
@stop
