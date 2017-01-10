define([
    'base',
    'entities/job',
    'apps/jobs/create_student_job/views/layout'
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
            this.studentUuid = options.studentUuid;

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
            this.job.saveForStudent(this.studentUuid, attributes);
        },

        onSyncSuccess : function () {
            _.toast('Job created!', 'success');
            this.onDestroy();
        },

        onSyncError : function () {
        },

        onDestroy : function () {
            var url = laroute.route('admin.students.jobs.index', {uuid: this.studentUuid});

            return window.location = url;
        }

    }));

});
