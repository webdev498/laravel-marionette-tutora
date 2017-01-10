@extends('_.layouts.default', [
    'page_class' => 'page--tutors page--admin'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav.authed.admin')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0" style="display: inline-block">Tutors</h4>
            <a href="{{ route('admin.transgressions.index') }}" class="[ btn btn--small ] u--mt--" style="float:right;">Transgressions</a>
            
            <a
                    href="{{ route('admin.background_checks.index') }}"
                    class="[ btn btn--small ] u--mt--" style="float:right;"
                    >
                Background checks
            </a>
        </div>
    </div>

    <div class="[ band band--ruled band--flush ]">
        <div class="wrapper">
            <div class="[ tabs tabs--full ]">
                <ul class="tabs__list ">
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.tutors.index') }}"
                          class="[ tabs__link tabs__link--compact @if ( ! $filter) tabs__link--active @endif ]"
                        >
                            All
                        </a>
                    </li>
                    @foreach ([
                        'Task'                       => 'All Tasks',
                        'TaskCategoryRebook'         => 'Rebook',
                        'TaskCategoryCancelled'      => 'Cancelled',
                        'TaskCategoryFirst'          => 'First',
                        'TaskCategoryFirstWithStudent'=> 'First With Student',
                        'TaskCategoryRefund'         => 'Refund',
                        'TaskCategoryDisintermediating'  => 'Disintermediating',
                        'TaskCategoryLessonCount'    => 'Lesson Count',
                        'Review'                     => 'For Review',
                        'TaskCategoryTransgression'  => 'Transgressions',
                        'ProfileScore'               => 'Low Profile Score',
                        
                    ] as $key => $value)
                        <li class="tabs__item">
                            <a href="{{ relroute('admin.tutors.index', ['filter' => $key]) }}"
                            class="[ tabs__link tabs__link--compact @if ($filter === $key) tabs__link--active @endif ]"
                            >
                                {{ $value }}
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
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="2">Tutor</th>
                            <th>Statuses</th>
                            @if($filter == 'Review')
                                <th>Background check</th>
                                <th>ID</th>
                            @endif 

                            @if ($filter == 'BookingScore' || $filter == 'ProfileScore')
                                <th>Booking Score</th>
                                <th>Profile Score</th>
                            @endif

                            <th>Task</th>
                            <th>Edit</th>
                            @if($filter !== 'Review')
                                <th>Lesson</th>
                                <th>Message</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($tutors->meta->count > 0)
                            @foreach ($tutors->data as $tutor)
                                <tr>
                                    <!-- Tutor -->
                                    <td>
                                        <a href="{{ route('tutor.profile.show', ['uuid' => $tutor->uuid]) }}">
                                            {{ $tutor->private->name }}
                                        </a><br>
                                        {{ $tutor->uuid }}
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $tutor->private->email }}""small">
                                            {{ $tutor->private->email }}
                                        </a><br>
                                        <a href="tel:{{ $tutor->private->telephone }}""small">
                                            {{ $tutor->private->telephone }}
                                        </a>
                                    </td>                                    

                                    <!-- Statuses -->
                                    <td>
                                        {{ $tutor->profile->status }}<br>
                                        {{ $tutor->profile->admin_status }}
                                    </td>


                                    @if($filter == 'Review')
                                    <!-- Background checks -->
                                    <td>
                                        <abbr title="{{ ucfirst($tutor->background_checks->background_status) }}">
                                            @if ($tutor->background_checks->background_status === 'approved')
                                                &#10004;
                                            @elseif ($tutor->background_checks->background_status === 'pending')
                                                &#126;
                                            @else
                                                &#10008;
                                            @endif
                                        </abbr>
                                    </td>

                                    <!-- ID -->
                                    <td>
                                        @if ($tutor->identity_document)
                                            <abbr title="{{ $tutor->identity_document->status }}">
                                                @if ($tutor->identity_document->status === 'verified')
                                                    &#10004;
                                                @elseif ($tutor->identity_document->status === 'unverified')
                                                    &#10008;
                                                @else
                                                    ~
                                                @endif
                                            </abbr>
                                        @endif
                                    </td>
                                    @endif

                                    <!-- Booking & Profile Score -->
                                    @if ($filter == 'BookingScore' || $filter == 'ProfileScore')
                                        <td>{{ $tutor->profile->booking_score }}</td>
                                        <td>{{ $tutor->profile->profile_score }}</td>
                                    @endif

                                    <!-- Task -->
                                    <td>
                                        @if (count($tutor->tasks) > 0)
                                            <span class="circle circle--{{$tutor->tasks[0]->category}}"></span>{{ $tutor->tasks[0]->short_body }}<br>
                                            <em>{{ $tutor->tasks[0]->action_at->short }}</em>
                                        @endif
                                    </td>

                                    <!-- Edit -->
                                    <td class="u-vam tac">

                                        <a href="{{ route('admin.tutors.show', ['uuid' => $tutor->uuid])}}">
                                            <span title="Show tutor" class="icon icon--edit"></span>
                                        </a>
                                        
                                    </td>
                                    @if($filter !== 'Review')
                                        <td class="u-vam tac">
                                            <a href="{{ route('admin.tutors.lessons.index', ['uuid' => $tutor->uuid])}}">
                                                <span title="Show tutor" class="icon icon--eye"></span>
                                            </a>
                                        </td>
                                        <td class="u-vam tac">
                                            <a href="{{ route('admin.tutors.relationships.index', ['uuid' => $tutor->uuid])}}">
                                                <span title="Show tutor" class="icon icon--message"></span>
                                            </a>
                                            
                                        </td>
                                    @endif

                                </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="8">
                                No results
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                @if ($tutors->meta->count > 0)
                    {!! $tutors->meta->pagination !!}
                @endif
            </div><!--
        --></div>
    </div>
@stop
