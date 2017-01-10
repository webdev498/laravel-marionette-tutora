define([
    'base',
    'entities/job',
    'entities/message',
    'apps/jobs/message_student/views/layout'
], function (
    Base,
    Job,
    Message,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController', 'DialogueController'],

        initialize : function (options) {
            this.job     = options.job;
            this.message = new Message.model();

            this.message.set('to', this.job.student.get('uuid'));

            this.view = new LayoutView({
                job   : this.job,
                model : this.message
            });

            this.listenTo(this.view, 'show', this.onShow);
            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);
            this.listenTo(this.view, 'nextStep', this.nextStep);

            this.listenTo(this.message, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.message, 'sync', this.onSyncSuccess);
            this.listenTo(this.message, 'error', this.onSyncError);
            this.listenTo(this.message, 'invalid', this.onInvalid);

            this.region.show(this.view);
        },

        onShow: function() {
        },

        save : function (attributes) {
            this.message.saveForJob(this.job.get('uuid'), attributes);
        },

        nextStep : function (attributes) {
            this.message.set(attributes, {validate: true});

            if(this.message.isValid()) {
                this.view.switchLayout();
            }
        },

        onSyncSuccess : function () {
            this.view.showConfirmation();
            this.job.tutor.set('applied', true);
            this.job.tutor.set('applicationMessage', this.message.get('body'));
        },

        onSyncError : function (model, response) {
            if(response.responseJSON.message == _.lang('exceptions.jobs.closed')) {
                var url = laroute.route('tutor.jobs.index');
                return window.location = url;
            }
        },

        onDestroy : function () {
        }

    }));

});
