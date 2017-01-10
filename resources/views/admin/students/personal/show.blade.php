@extends('admin.students._.layouts.show')

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
                    <tr>
                        <th>UUID</th>
                        <td>
                            {{ $student->uuid }}
                        </td>
                    </tr>
                    <!-- Status -->
                    <tr>
                        <th>Statuses</th>
                        <td>
                            {{ $student->status }}
                        </td>
                    </tr>
                    
                    <!-- Contact -->
                    <tr>
                        <th>Telephone</th>
                        <td>
                            <a href="tel:{{ $student->private->telephone }}""small">
                                {{ $student->private->telephone }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>
                            <a href="mailto:{{ $student->private->email }}""small">
                                {{ $student->private->email }}
                            </a>
                        </td>
                    </tr>
                    <!-- Search -->
                    <tr>
                        <th>Last Search</th>
                        <td>
                            @if (count($student->searches) > 0)
                                @foreach($student->searches as $search)
                                    @if ($search->location) {{ $search->location }}  <br>@endif
                                    @if ($search->subject) {{ $search->subject->title }} <br>@endif
                                @endforeach
                            @endif
                        </td>
                    </tr>
                    <!-- Receive tuition requests? -->
                    <tr>
                        <th>Receive Requests?</th>
                        <td>
                            {{ $student->settings->receive_requests }}
                        </td>
                    </tr>
                    <!-- Addresses -->
                    <tr>
                        <th>Addresses</th>
                        <td>
                            Default
                            <address>
                                {{ $student->addresses->default->line_1 }},
                                {{ $student->addresses->default->line_2 }},
                                {{ $student->addresses->default->line_3 }},
                                {{ $student->addresses->default->postcode }}
                            </address>
                            <br>
                            Billing
                            <address>
                                {{ $student->addresses->billing->line_1 }},
                                {{ $student->addresses->billing->line_2 }},
                                {{ $student->addresses->billing->line_3 }},
                                {{ $student->addresses->billing->postcode }}
                            </address>
                        </td>
                    </tr>
                    <!-- Timestamps -->
                    <tr>
                        <th>Created</th>
                        <td>{{ $student->created_at->long }}</td>
                    </tr>
                    <tr>
                        <th>Updated</th>
                        <td>{{ $student->updated_at->long }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <a href="{{ route('admin.students.personal.edit', ['student' => $student->uuid]) }}" class="[ btn btn--small btn--squared btn--full ]">Edit</a>
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
                    @foreach ($student->tasks as $task)
                        <tr>
                            <td class="js-task" data-task-id="{{ $task->id }}" data-task-body="{{ $task->body }}" data-task-action_at="{{ $task->action_at->value }}" data-task-category="{{ $task->category }}" >
                                <span class="circle circle--{{ $task->category }}"></span>{{ $task->body }}<br>
                                <em>{{ $task->action_at->short }}</em>
                            </td>
                            <td class="tar">
                                <a href="#" class="js-edit-task" data-task="{{ $task->id }}">
                                    <span title="Edit booking" class="icon icon--edit u-mb--"></span>
                                </a>
                                <form method="post" action="{{ route('admin.students.tasks.destroy', [
                                    'student' => $student->uuid,
                                    'task'  => $task->id
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
                            <form method="post" action="{{ route('admin.students.tasks.store', ['uuid' => $student->uuid]) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="text" name="body" placeholder="Task..." class="[ input input--squared input--full ]">
                                <input type="date" name="action_at" class="[ input input--squared input--full ]">
                                <select name="category">
                                    @foreach ([
                                        'general',
                                        'failed_payment',
                                        'expired_lesson',
                                        'pending_lesson',
                                        'not_replied',
                                        'mismatched_no_job',
                                        'mismatched_has_job',
                                    ] as $category)
                                        <option>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                               
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
                            <form method="post" action="{{ route('admin.students.notes.update', ['uuid' => $student->uuid]) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="put">
                                <textarea
                                    name="body"
                                    placeholder="Notes..."
                                    class="[ input input--squared input--full ]"
                                    style="min-height: 300px;"
                                >{{ $student->note->body }}</textarea>
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
                    var category = $task.data('task-category');
                    
                    var general_selected = category == 'general' ? ' selected="selected"' : '';
                    var failed_payment_selected = category == 'failed_payment' ? ' selected="selected"' : '';
                    var expired_lesson_selected = category == 'expired_lesson' ? ' selected="selected"' : '';
                    var pending_lesson_selected = category == 'pending_lesson' ? ' selected="selected"' : '';
                    var not_replied_selected = category == 'not_replied' ? ' selected="selected"' : '';
                    var mismatched_no_job_selected = category == 'mismatched_no_job' ? ' selected="selected"' : '';
                    var mismatched_has_job_selected = category == 'mismatched_has_job' ? ' selected="selected"' : '';

        
                    $task.html([
                            '<form method="post" action="/admin/students/{{ $student->uuid}}/tasks/' + id + '">',
                            '<input type="hidden" name="_token" value="{{ csrf_token() }}">',
                            '<textarea name="body" placeholder="Task..." class="[ input input--squared input--full ] u-mb-" style="min-height: 100px">' + body + '</textarea>',
                            '<input type="date" value="' + action_at + '" name="action_at" class="[ input input--squared input--full ]">',
                             '<select name="category">',
                                    
                                        
                                
                                        '<option' + general_selected + '>',
                                            'general', 
                                        '</option>',
                                        '<option' + failed_payment_selected + '>',
                                            'failed_payment', 
                                        '</option>',
                                        '<option' + expired_lesson_selected + '>',
                                            'expired_lesson', 
                                        '</option>',
                                        '<option' + pending_lesson_selected + '>',
                                            'pending_lesson', 
                                        '</option>',
                                        '<option' + not_replied_selected + '>',
                                            'not_replied', 
                                        '</option>',
                                        '<option' + mismatched_no_job_selected + '>',
                                            'mismatched_no_job', 
                                        '</option>',
                                        '<option' + mismatched_has_job_selected + '>',
                                            'mismatched_has_job', 
                                        '</option>',
                                        

                                '</select>',
                            '<button class="[ btn btn--small btn--squared btn--full ] u-mt-">Save</button>',
                        '</form>'
                    ].join(''));
                }

                return false;
            });
        }).call(this);
    </script>
@stop

