define([
    'base',
    'entities/job',
    'entities/message',
    'apps/jobs/view_message/views/layout'
], function (
    Base,
    Job,
    Message,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['DialogueController'],

        initialize : function (options) {
            this.job     = options.job;

            this.view = new LayoutView({
                model : this.job
            });

            this.listenTo(this.view, 'destroy', this.destroy);

            this.region.show(this.view);
        },

        onDestroy : function () {
        }

    }));

});
