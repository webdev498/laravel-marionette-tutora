@extends('admin.tutors._.layouts.edit')

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
                            <a href="{{ route('tutor.profile.show', ['uuid' => $tutor->uuid]) }}">
                                {{ $tutor->uuid }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Statuses<br>&nbsp;</th>
                        <td>
                            {{ $tutor->profile->status }}<br>
                            {{ $tutor->profile->admin_status }}
                        </td>
                    </tr>
                    <tr>
                        <th>Summary</th>
                        <td>{{ $tutor->profile->summary }}</td>
                    </tr>
                    <tr>
                        <th>Quality</th>
                        <td>{{ $tutor->profile->quality }}</td>
                    </tr>
                   
                    <tr>
                        <th>Featured</th>
                        <td>{{ $tutor->profile->is_featured ? '&#10004;' : '&#10008;' }}</td>
                    </tr>
                    <tr>
                        <th>Telephone</th>
                        <td>
                            <a href="tel:{{ $tutor->private->telephone }}""small">
                                {{ $tutor->private->telephone }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>
                            <a href="mailto:{{ $tutor->private->email }}""small">
                                {{ $tutor->private->email }}
                            </a>
                        </td>
                    </tr>
                     <tr>
                        <th>Profile Score</th>
                        <td>{{ $tutor->profile->profile_score }}</td>
                    </tr>
                    <tr>
                        <th>Booking Score</th>
                        <td>{{ $tutor->profile->booking_score }}</td>
                    </tr>
                    <tr>
                        <th>Addresses</th>
                        <td>
                            Default
                            <address>
                                {{ implode(', ', array_filter([
                                    $tutor->addresses->default->line_1,
                                    $tutor->addresses->default->line_2,
                                    $tutor->addresses->default->line_3,
                                    $tutor->addresses->default->postcode
                                ])) ?: 'n/a' }}
                            </address>
                            <br>
                            Billing
                            <address>
                                {{ implode(', ', array_filter([
                                    $tutor->addresses->billing->line_1,
                                    $tutor->addresses->billing->line_2,
                                    $tutor->addresses->billing->line_3,
                                    $tutor->addresses->billing->postcode
                                ])) ?: 'n/a' }}
                            </address>
                        </td>
                    </tr>

                    <!-- Background check -->
                    <tr>
                        <th>Background check</th>
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
                    </tr>

                    <!-- ID -->
                    <tr>
                        <th>ID</th>
                        <td>
                            @if ($tutor->identity_document)
                                <abbr title="{{ $tutor->identity_document->status }}">
                                    <a href="{{ $tutor->identity_document->src }}" target="_blank">
                                        @if ($tutor->identity_document->status === 'verified')
                                                &#10004;
                                            </a>
                                        @elseif ($tutor->identity_document->status === 'unverified')
                                            &#10008;
                                        @else
                                            ~
                                        @endif
                                    </a>
                                </abbr>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Transgressions</th>
                        <td>{{ $tutor->admin->transgressions }}</td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td>{{ $tutor->created_at->long }}</td>
                    </tr>
                    <tr>
                        <th>Updated</th>
                        <td>{{ $tutor->updated_at->long }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <a href="{{ route('admin.tutors.personal.edit', ['tutor' => $tutor->uuid]) }}" class="[ btn btn--small btn--squared btn--full ]">Edit</a>
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
                    @foreach ($tutor->tasks as $task)
                        <tr>
                            <td class="js-task" data-task-id="{{ $task->id }}" data-task-body="{{ $task->body }}" data-task-action_at="{{ $task->action_at->value }}" data-task-category="{{ $task->category }}" >
                                <span class="circle circle--{{ $task->category }}"></span>{{ $task->body }}<br>
                                <em>{{ $task->action_at->short }}</em>
                            </td>
                            <td class="tar">
                                <a href="#" class="js-edit-task" data-task="{{ $task->id }}">
                                    <span title="Edit booking" class="icon icon--edit u-mb--"></span>
                                </a>
                                <form method="post" action="{{ route('admin.tutors.tasks.destroy', [
                                    'tutor' => $tutor->uuid,
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
                            <form method="post" action="{{ route('admin.tutors.tasks.store', ['uuid' => $tutor->uuid]) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="text" name="body" placeholder="Task..." class="[ input input--squared input--full ]">
                                <input type="date" name="action_at" class="[ input input--squared input--full ]">
                                <select name="category" style="width:100%">
                                    @foreach ([
                                        'general',
                                        'cancelled_first_lesson',
                                        'rebook',
                                        'refund',
                                        'disintermediating',
                                        'first_lesson_no_rebook',
                                        'first_lesson_rebook',
                                        'first_with_student_rebook',
                                        'first_with_student_no_rebook',
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
                            <form method="post" action="{{ route('admin.tutors.notes.update', ['uuid' => $tutor->uuid]) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="put">
                                <textarea
                                    name="body"
                                    placeholder="Notes..."
                                    class="[ input input--squared input--full ]"
                                    style="min-height: 300px;"
                                >{{ $tutor->note->body }}</textarea>
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
                    var cancelled_first_lesson_selected = category == 'cancelled_first_lesson' ? ' selected="selected"' : '';
                    var rebook_selected = category == 'rebook' ? ' selected="selected"' : '';
                    var refund_selected = category == 'refund' ? ' selected="selected"' : '';
                    var disintermediating_selected = category == 'disintermediating' ? ' selected="selected"' : '';
                    var first_lesson_rebook_selected = category == 'first_lesson_rebook' ? ' selected="selected"' : '';
                    var first_lesson_no_rebook_selected = category == 'first_lesson_no_rebook' ? ' selected="selected"' : '';
                    var first_with_student_rebook_selected = category == 'first_with_student_rebook' ? ' selected="selected"' : '';
                    var first_with_student_no_rebook_selected = category == 'first_with_student_no_rebook' ? ' selected="selected"' : '';

                    $task.html([
                            '<form method="post" action="/admin/tutors/{{ $tutor->uuid}}/tasks/' + id + '">',
                            '<input type="hidden" name="_token" value="{{ csrf_token() }}">',
                            '<textarea name="body" placeholder="Task..." class="[ input input--squared input--full ] u-mb-" style="min-height: 100px">' + body + '</textarea>',
                            '<input type="date" value="' + action_at + '" name="action_at" class="[ input input--squared input--full ]">',

                            '<select name="category" style="width:100%"">',
                                    
                                        
                                
                                        '<option' + general_selected + '>',
                                            'general', 
                                        '</option>',
                                        '<option' + cancelled_first_lesson_selected + '>',
                                            'cancelled_first_lesson', 
                                        '</option>',
                                        '<option' + rebook_selected + '>',
                                            'rebook', 
                                        '</option>',
                                        '<option' + refund_selected + '>',
                                            'refund', 
                                        '</option>',
                                        '<option' + disintermediating_selected + '>',
                                            'disintermediating', 
                                        '</option>',
                                        '<option' + first_lesson_rebook_selected + '>',
                                            'first_lesson_rebook', 
                                        '</option>',
                                        '<option' + first_lesson_no_rebook_selected + '>',
                                            'first_lesson_no_rebook', 
                                        '</option>',
                                        '<option' + first_with_student_rebook_selected + '>',
                                            'first_with_student_rebook', 
                                        '</option>', 
                                        '<option' + first_with_student_no_rebook_selected + '>',
                                            'first_with_student_no_rebook', 
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

