define([
    'base',
    'entities/job',
    'apps/jobs/create_job/views/layout'
], function (
    Base,
    Job,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController', 'DialogueController'],

        initialize : function (options) {

            this.region      = options.region;
            this.job         = Job.model();

            this.view = new LayoutView({
                model: this.job
            });

            this.job.set('action', 'student-registration');
            this.job.set('by_request', '1');

            this.listenTo(this.view, 'show', this.onShow);
            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.listenTo(this.job, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.job, 'sync', this.onSyncSuccess);
            this.listenTo(this.job, 'error', this.onSyncError);
            this.listenTo(this.job, 'invalid', this.onInvalid);

            this.region.show(this.view);
        },

        onShow: function() {
        },

        save : function (attributes) {
            this.job.save(attributes);
        },

        onSyncSuccess : function () {
            _.toast('Job created!', 'success');
            this.redirectBack();
        },

        onSyncError : function () {
        },

        redirectBack: function() {
            var url = laroute.route('admin.jobs.index');

            return window.location = url;
        },

        onDestroy : function () {
            var url = laroute.route('admin.jobs.index');

            this.app.history.back(url);
        }

    }));

});
