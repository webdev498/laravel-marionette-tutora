@extends('admin.students._.layouts.show')

@section('show')
    <div class="layout"><!--
        --><div class="layout__item">
            <div class="osx">
                <table class="table">
                    <thead>
                        <th>Tutor</th>
                        <th>Status</th>
                        <th>Is Confirmed?</th>
                        <th>Job Application?</th>
                        <th>Last Message</th>
                        <th>Next task</th>
                        <th>&nbsp;</th>
                    </thead>
                    <tbody>
                        @foreach ($relationships as $relationship)
                            <tr>
                                <!-- Student -->
                                <td>
                                    <a href="{{ route('admin.tutors.show', ['tutor' => $relationship->tutor->uuid]) }}">
                                        {{ $relationship->tutor->private->name }}
                                    </a><br>
                                    {{ $relationship->tutor->uuid }}
                                </td>

                                <!-- Status -->
                                <td>
                                    {{ $relationship->status }}<br>
                                </td>
                                <!-- Is confirmed? -->
                                <td>
                                    @if($relationship->is_confirmed) <span class="icon icon--confirm"></span>@endif
                                </td>
                                <!-- Is Job Application? -->
                                <td>
                                    @if($relationship->is_application) <span class="icon icon--confirm"></span>@endif
                                </td>

                                <!-- Last message -->
                                <td>
                                    @if (count($relationship->message->lines) === 1)
                                        {{ $relationship->message->lines[0]->short_body }}<br>
                                        <em>{{ $relationship->message->lines[0]->time->short }}</em>
                                    @else
                                        &nbsp;
                                    @endif
                                </td>

                                <!-- Next task -->
                                <td>
                                    @if (count($relationship->tasks) === 1)
                                        {{ $relationship->tasks[0]->short_body }}<br>
                                        <em>{{ $relationship->tasks[0]->action_at->short }}</em>
                                    @else
                                        ~
                                    @endif
                                </td>

                                <!-- Edit -->
                                <td class="u-vam tar">
                                    <a href="{{ route('admin.relationships.show', ['id' => $relationship->id])}}">
                                        <span title="Show relationship" class="icon icon--eye"></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div><!--
    --></div>
@stop
