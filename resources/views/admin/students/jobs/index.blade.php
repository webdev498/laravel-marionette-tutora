@extends('admin.students._.layouts.show')

@section('show')
    <div class="layout"><!--
        --><div class="layout__item">
            <div class="osx">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Subject</th>
                            <th>Postcode</th>
                            <th>No. of Replies</th>
                            <th class="u-vam tar">View and Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobs as $job)
                            <tr>
                                <!-- Status -->
                                <td>
                                    {{ $job->statusTitle }}
                                </td>

                                <!-- Subject -->
                                <td>
                                    @if($job->subject) {{ $job->subject->title }} @endif
                                </td>

                                <!-- Postcode -->
                                <td>
                                    {{ $job->location->postcode }}
                                </td>

                                <!-- No. of Replies -->
                                <td>
                                    {{ $job->repliesNumber }}
                                </td>

                                <!-- View and Edit -->
                                <td class="u-vam tar">
                                    <a href="{{ relroute('admin.jobs.details.edit', ['uuid' => $job->uuid]) }}">
                                        <span title="Show relationship" class="icon icon--eye"></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="[ band ]">
            <div class="wrapper">
                <a style="float:right;" class="[ btn btn--small ] u--mt--" data-js href="{{ relroute('admin.students.jobs.create', ['uuid' => $student->uuid]) }}">
                    Create a Job
                </a>
            </div>
        </div>
    </div>
@stop
