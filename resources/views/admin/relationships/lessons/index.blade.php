@extends('admin.relationships._.layouts.show')

@section('show')
    <div class="layout"><!--
        --><div class="layout__item">
            <div class="oxs">
                <table class="table">
                    <thead>
                        <th>UUID</th>
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
                                    {{ $booking->status->admin->charge_status }}
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
                                    <a href="#">
                                        <span title="Edit lesson" class="icon icon--edit"></span>
                                    </a>
                                    <a href="#">
                                        <span title="Cancel lesson" class="icon icon--delete"></span>
                                    </a>
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

