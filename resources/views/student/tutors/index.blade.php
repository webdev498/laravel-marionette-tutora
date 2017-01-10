@extends('_.layouts.default', [
  'page_class' => 'page--student page--tutors'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0">@lang('dashboard.student.tutors.heading')</h4>
        </div>
    </div>

    @if ( ! Cookie::has('dismissable_student_tutors_introduction'))
        <div class="[ band band--ui band--ruled ] [ js-dismissable ]" data-dismissable-id="student_tutors_introduction">
            <div class="wrapper">
                <span class="band__close [ icon icon--cancel ] [ js-close ]"></span>
                <p>@lang('dashboard.student.tutors.introduction')</p>
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
                                <th colspan="3">Next lesson</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($tutors->meta->count > 0)
                                @foreach ($tutors->data as $tutor)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tutor.profile.show', ['uuid' => $tutor->uuid])}}">
                                                {{ $tutor->name }}
                                            </a>
                                        </td>
                                        @if (
                                            count($tutor->relationships) === 1 &&
                                            count($tutor->relationships[0]->lessons) === 1 &&
                                            count($tutor->relationships[0]->lessons[0]->bookings) === 1
                                        )
                                            <?php $lesson  = $tutor->relationships[0]->lessons[0]; ?>
                                            <?php $booking = $lesson->bookings[0]; ?>

                                            <td>{{ $lesson->subject->title }}</td>
                                            <td>
                                                <abbr title="{{ $booking->date->long }}">
                                                    {{ $booking->date->short }}
                                                </abbr><!--
                                                --> at <!--
                                                --><abbr title="{{ $booking->duration }} in duration">
                                                    {{ $booking->time->start }} - {{ $booking->time->finish }}
                                                </abbr>
                                            </td>
                                            <td>
                                                <abbr title="Based on a rate of {{ $booking->rate }} per hour.">{{ $booking->price }}</abbr>
                                            </td>
                                        @else
                                            <td colspan="3">
                                                n/a<!--
                                                @if (
                                                    count($tutor->relationships) === 1 &&
                                                    $tutor->relationships[0]->message
                                                )-->, <a href="{{ route('student.messages.show', [
                                                    'uuid' => $tutor->relationships[0]->message->uuid
                                                    ]) }}">requrest one now?</a><!--
                                                @endif
                                                -->
                                            </td>
                                        @endif
 
                                        <td class="tar">
                                            @if ($tutor->actions->review)
                                                <a href="{{ relroute('review.create', [
                                                    'tutor' => $tutor->uuid
                                                ]) }}" data-js>
                                                    <span title="Leave a review" class="icon icon--edit"></span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">
                                        <em>@lang('dashboard.student.tutors.none')</em>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if (count($tutors->data->toArray()) > 0)
                    {!! $tutors->meta->pagination !!}
                @endif
            </div><!--
        --></div>
    </div>
@stop
