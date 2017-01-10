@extends('admin.relationships._.layouts.show')

@section('show')
    <div class="layout"><!--
        --><div class="layout__item one-third">
            <table class="table">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- ID -->
                    <tr>
                        <th>ID</th>
                        <td>
                            {{ $relationship->id }}
                        </td>
                    </tr>

                    <!-- Status -->
                    <tr>
                        <th>Status</th>
                        <td>
                            {{ $relationship->status }}
                        </td>
                    </tr>

                    <!-- Search -->
                    <tr>
                        <th>Search</th>
                        <td>
                            @if (count($relationship->searches) >= 1)
                                {{ $relationship->searches[0]->location }} <br>
                                @if ($relationship->searches[0]->subject)
                                    {{ $relationship->searches[0]->subject->title }}
                                @endif
                            @endif
                        </td>
                    </tr>

                    <!-- Tutor -->
                    <tr>
                        <th>Tutor</th>
                        <td>
                            <a href="{{ route('tutor.profile.show', ['uuid' => $relationship->tutor->uuid]) }}">
                                {{ $relationship->tutor->private->name }}
                            </a><br>
                            <a href="mailto:{{ $relationship->tutor->private->email }}">
                                {{ $relationship->tutor->private->email }}
                            </a><br>
                            <a href="tel:{{ $relationship->tutor->private->telephone }}">
                                {{ $relationship->tutor->private->telephone }}
                            </a><br>
                            {{ $relationship->tutor->uuid }}
                        </td>
                    </tr>

                    <!-- Student -->
                    <tr>
                        <th>Student</th>
                        <td>
                            {{ $relationship->student->private->name }}
                            <br>
                            <a href="mailto:{{ $relationship->student->private->email }}">
                                {{ $relationship->student->private->email }}
                            </a><br>
                            @if ($relationship->student->private->telephone)
                                <a href="tel:{{ $relationship->student->private->telephone }}">
                                    {{ $relationship->student->private->telephone }}
                                </a><br>
                            @endif
                            {{ $relationship->student->uuid }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <a href="{{ route('admin.relationships.details.edit', ['relationship' => $relationship->id]) }}" class="[ btn btn--small btn--squared btn--full ]">Edit</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div><!--

        --><div class="layout__item one-third">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2">Tasks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($relationship->tasks as $task)
                        <tr>
                            <td class="js-task" data-task-id="{{ $task->id }}" data-task-body="{{ $task->body }}" data-task-action_at="{{ $task->action_at->value }}">
                                {{ $task->body }}<br>
                                <em>{{ $task->action_at->short }}</em>
                            </td>
                            <td class="tar">
                                <a href="#" class="js-edit-task" data-task="{{ $task->id }}">
                                    <span title="Edit booking" class="icon icon--edit u-mb--"></span>
                                </a>
                                <form method="post" action="{{ route('admin.relationships.tasks.destroy', [
                                    'relationship' => $relationship->id,
                                    'task'         => $task->id
                                ]) }}">
                                    {{ method_field('DELETE') }}
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button title="Cancel booking" class="icon icon--delete"></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">
                            <form method="post" action="{{ route('admin.relationships.tasks.store', ['id' => $relationship->id]) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="text" name="body" placeholder="Task..." class="[ input input--squared input--full ]">
                                <input type="date" name="action_at" class="[ input input--squared input--full ]">
                                <button class="[ btn btn--small btn--squared btn--full ] u-mt-">Save</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div><!--

        --><div class="layout__item one-third">
            <table class="table">
                <thead>
                    <tr>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <form method="post" action="{{ route('admin.relationships.notes.update', ['id' => $relationship->id]) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="put">
                                <textarea
                                    name="body"
                                    placeholder="Notes..."
                                    class="[ input input--squared input--full ]"
                                    style="min-height: 300px;"
                                >{{ $relationship->note->body }}</textarea>
                                <button class="[ btn btn--small btn--squared btn--full ] u-mt-">Save</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div><!--
    --></div>
@stop

@section('scripts')
    <script src="/vendor/jquery/dist/jquery.min.js"></script>
    <script>
        (function () {
            $('.js-edit-task').on('click', function (e) {
                if (e.preventDefault) {
                    e.preventDefault();
                }

                var $el   = $(e.currentTarget);
                var id    = $el.data('task');
                var $task = $('.js-task[data-task-id="' + id + '"]');

                if ($task.length === 1) {
                    var body      = $task.data('task-body');
                    var action_at = $task.data('task-action_at');

                    $task.html([
                        '<form method="post" action="/admin/relationships/{{ $relationship->id }}/tasks/' + id + '">',
                            '<input type="hidden" name="_token" value="{{ csrf_token() }}">',
                            '<textarea name="body" placeholder="Task..." class="[ input input--squared input--full ] u-mb-" style="min-height: 100px">' + body + '</textarea>',
                            '<input type="date" value="' + action_at + '" name="action_at" class="[ input input--squared input--full ]">',
                            '<button class="[ btn btn--small btn--squared btn--full ] u-mt-">Save</button>',
                        '</form>'
                    ].join(''));
                }

                return false;
            });
        }).call(this);
    </script>
@stop

