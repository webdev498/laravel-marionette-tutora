@extends('_.layouts.default', [
  'page_class' => 'page--tutor page--students'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0">@lang('dashboard.tutor.students.heading')</h4>
        </div>
    </div>

    @if ( ! Cookie::has('dismissable_tutor_students_introduction'))
        <div class="[ band band--ui band--ruled ] [ js-dismissable ]" data-dismissable-id="tutor_students_introduction">
            <div class="wrapper">
                <span class="band__close [ icon icon--cancel ] [ js-close ]"></span>
                <p>@lang('dashboard.tutor.students.introduction')</p>
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
                            @if ($students->meta->count > 0)
                                @foreach ($students->data as $student)
                                    <tr>
                                        <td>{{ $student->name }}</td>
                                        @if (
                                            count($student->relationships) === 1 &&
                                            count($student->relationships[0]->lessons) === 1 &&
                                            count($student->relationships[0]->lessons[0]->bookings) === 1
                                        )
                                            <?php $lesson  = $student->relationships[0]->lessons[0]; ?>
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
                                                n/a, <a href="{{ relroute('tutor.lessons.create', [
                                                    'student' => $student->uuid
                                                ]) }}" data-js>book one now?</a>
                                            </td>
                                        @endif
                                        <td class="tar">
                                            @if (count($student->relationships) === 1)
                                                <a href="{{ route('tutor.messages.show', [
                                                        'id' => $student->relationships[0]->message->uuid
                                                ]) }}">
                                                    <span class="icon icon--message"></span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">
                                        <em>@lang('dashboard.tutor.students.none')</em>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if ($students->meta->count > 0)
                    {!! $students->meta->pagination !!}
                @endif
            </div><!--
        --></div>
    </div>
@stop
