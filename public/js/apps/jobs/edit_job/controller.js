define([
    'base',
    'entities/job',
    'apps/jobs/edit_job/views/layout',
    'apps/jobs/edit_job/views/deleteConfirmation',
    'apps/jobs/edit_job/views/initialMessage',
    'apps/jobs/edit_job/views/replies'
], function (
    Base,
    Job,
    LayoutView,
    DeleteConfirmationView,
    InitialMessageView,
    RepliesView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController'],

        initialize : function (options) {
            this.app     = options.app;
            this.region  = options.region;
            this.job     = Job.model({'uuid' : options.uuid});

            _.progress().start();

            this.listenToOnce(this.job, 'sync', this.renderLayout);

            this.job.fetch();
        },

        renderLayout: function() {
            _.progress().done();

            this.view = new LayoutView({
                model: this.job
            });
            this.repliesView = new RepliesView({
                job         : this.job,
                collection  : this.job.replies
            });

            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);
            this.listenTo(this.view, 'render', this.onRender);
            this.listenTo(this.view, 'job:delete', this.deleteJob);

            this.listenTo(this.job, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.job, 'sync', this.onSyncSuccess);
            this.listenTo(this.job, 'error', this.onSyncError);
            this.listenTo(this.job, 'invalid', this.onInvalid);

            this.region.show(this.view);
            this.app.tutorJobReplies.show(this.repliesView);

            if(this.job.initialTutorMessage.get('tutor_name')) {
                this.initialMessageView = new InitialMessageView({
                    model  : this.job.initialTutorMessage
                });
                this.app.initialTutorMessage.show(this.initialMessageView);
            }
        },

        deleteJob: function() {
            this.deleteConfirmationView = new DeleteConfirmationView();
            this.listenTo(this.deleteConfirmationView, 'job:delete:confirmed', this.deleteJobConfirmed);

            this.app.dialogueRegion.show(this.deleteConfirmationView);
        },

        deleteJobConfirmed: function() {
            this.stopListening(this.job, 'sync');
            this.listenToOnce(this.job, 'destroy', this.onJobDestroyed);
            this.job.destroy({wait: true});
        },

        onJobDestroyed: function() {
            _.toast('Job was removed!', 'success');

            var redirect = laroute.route('admin.jobs.index');

            return window.location = redirect;
        },

        save : function (attributes) {
            this.job.save(attributes);
        },

        onSyncSuccess : function () {
            _.toast('Job saved!', 'success');

            this.view.enableSubmit();
        },

        onSyncError : function () {
            this.view.enableSubmit();
        }

    }));

});
