@extends('_.layouts.default', [
    'page_class' => 'page--relationships page--admin'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav.authed.admin')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0" style="display:inline;">Relationships</h4>

            <a
              href="{{ route('admin.relationships.create') }}"
              class="[ btn btn--small ] u--mt--" style="float:right;"
            >
                Create
            </a>
        </div>
    </div>

    <div class="[ band band--ruled band--flush ]">
        <div class="[ tabs tabs--full ]">
            <ul class="tabs__list">
                <li class="tabs__item">
                    <a href="{{ relroute('admin.relationships.index') }}"
                        class="[ tabs__link @if ( ! $filter) tabs__link--active @endif ]"
                    >
                        All
                    </a>
                </li>
                @foreach ([
                    
                    'chatting'  => 'Chatting',
                    'pending'   => 'Pending',
                    'confirmed' => 'Confirmed',
                    'no_reply'  => 'No Reply',
                    'mismatched'=> 'Mismatched'
                ] as $key => $value)
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.relationships.index', ['filter' => $key]) }}"
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
                <div class="oxs">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tutor</th>
                                <th>Student</th>
                                <th>Last message</th>
                                <th>Status</th>
                                <th>Task</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($relationships->data as $relationship)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.tutors.show', ['uuid' => $relationship->tutor->uuid]) }}">
                                            {{ $relationship->tutor->name }}
                                        </a><br>
                                        {{ $relationship->tutor->uuid }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.students.show', ['uuid' => $relationship->student->uuid]) }}">
                                            {{ $relationship->student->name }}
                                        </a><br>
                                        {{ $relationship->tutor->uuid }}
                                    </td>
                                    @if (count($relationship->message->lines) === 1)
                                        <td>
                                            {{ $relationship->message->lines[0]->short_body }}<br>
                                            <em>{{ $relationship->message->lines[0]->time->short }}</em>
                                        </td>
                                    @else
                                        <td>
                                            <em>n/a</em>
                                            <br>&nbsp;
                                        </td>
                                    @endif

                                    <td>
                                        {{ $relationship->status }}
                                        <br>&nbsp;
                                    </td>

                                    @if (count($relationship->tasks) === 1)
                                        <td>
                                            {{ $relationship->tasks[0]->short_body }}<br>
                                            <em>{{ $relationship->tasks[0]->action_at->short }}</em>
                                        </td>
                                    @else
                                        <td>
                                            <em>n/a</em>
                                            <br>&nbsp;
                                        </td>
                                    @endif

                                    <td>
                                        <a
                                        href="{{ route('admin.relationships.show', [
                                            'id' => $relationship->id,
                                        ]) }}"
                                        >
                                            <span title="Show" class="icon icon--eye"></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($relationships->meta->count > 0)
                    {!! $relationships->meta->pagination !!}
                @endif
            </div><!--
        --></div>
    </div>
@stop
