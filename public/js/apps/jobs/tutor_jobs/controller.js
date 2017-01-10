define([
    'base',
    'entities/job',
    'apps/jobs/tutor_jobs/views/list',
    'apps/jobs/message_student/controller',
    'apps/jobs/view_message/controller'
], function (
    Base,
    Job,
    CollectionView,
    CreateMessageController,
    ViewMessageController
) {

    return _.patch(Base.Controller.extend({

        mixins : [],

        initialize : function (options) {
            this.options = options;
            this.region  = options.region;
            this.jobs    = Job.currentCollection();

            this.renderLayout();
        },

        renderLayout: function() {
            this.view = new CollectionView({
                collection: this.jobs
            });

            this.listenTo(this.view, 'job:favourite', this.favouriteJob);
            this.listenTo(this.view, 'job:message:create', this.createMessage);
            this.listenTo(this.view, 'job:message:view', this.viewMessage);

            this.view.render();
        },

        favouriteJob: function(model) {
            var favourite = model.tutor.get('favourite');
            model.tutor.set('favourite', !favourite);
            model.favouriteJob();
        },

        createMessage: function(model) {
            return new CreateMessageController(_.extend(this.options, {
                'job'    : model,
                'region' : this.app.dialogueRegion
            }));
        },

        viewMessage: function(model) {
            return new ViewMessageController(_.extend(this.options, {
                'job'    : model,
                'region' : this.app.dialogueRegion
            }));
        }

    }));

});
