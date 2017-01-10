@extends('_.layouts.default', [
    'page_class' => 'page--jobs page--admin'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav.authed.admin')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0" style="display:inline;">Jobs</h4>

            <a
                    href="{{ relroute('admin.jobs.create') }}"
                    class="[ btn btn--small ] u--mt--" style="float:right;"
                    data-js
                    >
                Create a Job
            </a>
        </div>
    </div>

    <div class="[ band band--ruled band--flush ]">
        <div class="wrapper">
            <div class="[ tabs tabs--full ]">
                <ul class="tabs__list">
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.jobs.index') }}"
                           class="[ tabs__link @if ( ! $filter) tabs__link--active @endif ]"
                                >
                            All
                        </a>
                    </li>
                    @foreach ([
                        \App\Job::STATUS_PENDING,
                        \App\Job::STATUS_LIVE,
                        \App\Job::STATUS_CLOSED,

                    ] as $key => $value)
                        <li class="tabs__item">
                            <a href="{{ relroute('admin.jobs.index', ['filter' => $value]) }}"
                               class="[ tabs__link @if ($filter === $value) tabs__link--active @endif ]"
                                    >
                                {{ @trans('jobs.statuses')[$value] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="wrapper u-mt">
        <div class="layout"><!--
            --><div class="layout__item">
                <div class="osx">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="tac">Student Name</th>
                            <th class="tac">Status</th>
                            <th class="tac">Is Confirmed</th>
                            <th class="tac">By Applicant?</th>
                            <th class="tac">Time Open</th>
                            <th class="tac">Subject</th>
                            <th class="tac">Postcode</th>
                            <th class="tac">No. of Replies</th>
                            @if($filter = \App\Job::STATUS_CLOSED)
                                <th>Closed for</th>
                            @endif
                            <th class=" tac">View and Edit</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if ($jobs->meta->count > 0)

                                @foreach ($jobs->data as $job)
                                    <tr>
                                        <!-- Student Name -->
                                        <td class="tac">
                                            <a
                                                    href="{{ route('admin.students.show', [
                                                'uuid' => $job->student->uuid
                                              ]) }}"
                                                    >
                                                {{ $job->student->private->name }}
                                            </a>
                                        </td>

                                        <!-- Status -->
                                        <td class="tac">
                                            {{ $job->statusTitle }}
                                        </td>

                                        <!-- Is Confirmed -->
                                        <td class="tac">
                                            @if($job->isConfirmed) <span class="icon icon--confirm"></span>@endif
                                        </td>
                                        
                                        <!-- Is Confirmed By Applicant -->
                                        <td class="tac">
                                            @if($job->isConfirmedByApplicant) <span class="icon icon--confirm"></span>@endif
                                        </td>

                                        <!-- Opened for -->
                                        <td class="tac">
                                            @if($job->timeOpen) {{ $job->timeOpen->shortest }} @endif
                                        </td>

                                        <!-- Subject -->
                                        <td class="tac">
                                            {{ $job->subject ? $job->subject->title : 'Custom subject' }}
                                        </td>

                                        <!-- Postcode -->
                                        <td class="tac">
                                            {{ $job->location->postcode }}
                                        </td>

                                        <!-- No. of Replies -->
                                        <td class="tac">
                                            {{ $job->repliesNumber }}
                                        </td>
                                        @if($filter = \App\Job::STATUS_CLOSED)
                                        <!-- Closed for -->
                                        <td class="tac">
                                            {{ $job->closedFor }}
                                        </td>
                                        @endif

                                        <!-- View and Edit -->
                                        <td class="u-vam tac">
                                            <a href="{{ route('admin.jobs.details.edit', [
                                                'uuid' => $job->uuid,
                                              ]) }}">
                                                <span title="Show Job" class="icon icon--eye"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                            @endif
                        </tbody>
                    </table>
                </div>

                @if ($jobs->meta->count > 0)
                    {!! $jobs->meta->pagination !!}
                @endif
            </div><!--
        --></div>
    </div>
@stop
