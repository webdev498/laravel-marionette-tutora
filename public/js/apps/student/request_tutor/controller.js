define([
    'base',
    'entities/job',
    'apps/student/request_tutor/views/layout'
], function (
    Base,
    Job,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController', 'DialogueController'],

        initialize : function (options) {
            this.region = options.region;
            this.job    = new Job.model();

            this.view = new LayoutView({
                model: this.job
            });

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
            _.toast('Request sent!', 'success');
            this.success = true;
            this.view.destroy();
        },

        onSyncError : function () {
        },

        onDestroy : function () {
            if(this.success) {
                var url = laroute.route('student.dashboard.index', {dialogue: 'student_job_created'});
                this.app.history.navigate(url, {trigger: true});
            } else {
                var url = laroute.route('student.dashboard.index');
                this.app.history.back(url);
            }
        }

    }));

});
