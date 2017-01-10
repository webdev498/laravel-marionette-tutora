@extends('admin.tutors._.layouts.edit')

@section('show')
    <div class="layout [ js-account-region ]"><!--
        --><div class="layout__item one-half [ js-identification-region ]">
        </div><!--

        --><div class="layout__item one-half [ js-payment-region ]">
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
                            '<form method="post" action="/admin/tutors/{{ $tutor->uuid}}/tasks/' + id + '">',
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

