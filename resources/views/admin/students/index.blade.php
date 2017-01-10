@extends('_.layouts.default', [
    'page_class' => 'page--students page--admin'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav.authed.admin')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0">Students</h4>
        </div>
    </div>

    <div class="[ band band--ruled band--flush ]">
        <div class="[ tabs tabs--full ]">
            <ul class="tabs__list">
                <li class="tabs__item">
                    <a href="{{ relroute('admin.students.index') }}"
                        class="[ tabs__link @if ( ! $filter) tabs__link--active @endif ]"
                    >
                        All
                    </a>
                </li>
                @foreach ([
                    'failed_payment'    => 'Failed',
                    'expired_lesson'=> 'Expired',
                    'pending_lesson' => 'Pending',
                    'not_replied' => 'Not Replied',
                    'mismatched_no_job'   => 'Mismatched - No Job',
                    'mismatched_has_job'   => 'Mismatched - Has Job',
                ] as $key => $value)
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.students.index', ['filter' => $key]) }}"
                        class="[ tabs__link @if ($filter === $key) tabs__link--active @endif ]"
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
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="2">Student</th>
                            <th>Task</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($students->meta->count > 0)
                            @foreach ($students->data as $student)
                                <tr>
                                    <!-- Student -->
                                    <td>
                                        {{ $student->private->name }}<br>
                                        {{ $student->uuid }}
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $student->private->email }}""small">
                                            {{ $student->private->email }}
                                        </a><br>
                                        <a href="tel:{{ $student->private->telephone }}""small">
                                            {{ $student->private->telephone }}
                                        </a>
                                    </td>
                                    <!-- Task -->
                                    <td>
                                        @if (count($student->tasks) != 0)
                                            <span class="circle circle--{{$student->tasks[0]->category}}"></span>{{ $student->tasks[0]->short_body }}<br>
                                            <em>{{ $student->tasks[0]->action_at->short }}</em>
                                        @endif
                                    </td>

                                    <!-- Edit -->
                                    <td class="u-vam">
                                        <a href="{{ route('admin.students.show', ['uuid' => $student->uuid])}}">
                                            <span title="Show student" class="icon icon--eye"></span>
                                        </a>
                                    </td>
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

                @if ($students->meta->count > 0)
                    {!! $students->meta->pagination !!}
                @endif
            </div><!--
        --></div>
    </div>
@stop
