define([
    'base',
    'entities/user',
    'apps/jobs/edit_job/views/reply',
    'requirejs-text!apps/jobs/edit_job/templates/replies.html'
], function (
    Base,
    User,
    RowView,
    template
) {

    return _.patch(Base.CompositeView.extend({

        childView: RowView,

        childViewContainer: "tbody",

        template : _.template(template),

        initialize: function(options) {
            this.job = options.job;
        },

        templateHelpers : function () {
            return {
                'repliesNumber'  : this.job.get('repliesNumber')
            };
        }

    }));

});
