define([
    'base',
    'entities/job',
    'apps/jobs/tutor_view_job/views/layout',
    'apps/jobs/message_student/controller',
    'apps/jobs/view_message/controller'
], function (
    Base,
    Job,
    LayoutView,
    CreateMessageController,
    ViewMessageController
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController'],

        initialize : function (options) {
            this.app     = options.app;
            this.region  = options.region;
            this.job     = Job.model({'uuid' : options.uuid});

            _.progress().start();

            this.listenToOnce(this.job, 'sync', this.renderLayout);
            this.listenToOnce(this.job, 'error', this.syncError);

            this.job.fetch();
        },

        renderLayout: function() {
            _.progress().done();

            var redirect = false;
            if(this.job.get('statusTitle') === 'Closed') {
                _.toast(_.lang('exceptions.jobs.closed'), 'error');
                redirect = true;
            } else if(this.job.get('statusTitle') === 'Pending') {
                _.toast('Job is not found!', 'error');
                redirect = true;
            }

            if(redirect) {
                var url = laroute.route('tutor.jobs.index');
                return window.location = url;
            }

            this.view = new LayoutView({
                model: this.job
            });

            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);
            this.listenTo(this.view, 'render', this.onRender);

            this.listenTo(this.view, 'job:favourite', this.favouriteJob);
            this.listenTo(this.view, 'job:message:create', this.createMessage);
            this.listenTo(this.view, 'job:message:view', this.viewMessage);

            this.region.show(this.view);
        },

        syncError: function() {
            var url = laroute.route('tutor.jobs.index');

            this.app.history.navigate(url, {trigger: true});
            location.reload();
        },

        favouriteJob: function() {
            var favourite = this.job.tutor.get('favourite');
            this.job.tutor.set('favourite', !favourite);
            this.job.favouriteJob();
        },

        createMessage: function(model) {
            return new CreateMessageController(_.extend(this.options, {
                'job'    : this.job,
                'region' : this.app.dialogueRegion
            }));
        },

        viewMessage: function(model) {
            return new ViewMessageController(_.extend(this.options, {
                'job'    : this.job,
                'region' : this.app.dialogueRegion
            }));
        }

    }));

});
