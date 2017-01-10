define([
    'base',
    'entities/user',
    'entities/session_vars',
    'apps/autocomplete/subjects/controller',
    'apps/jobs/tutor_jobs/views/job_item'
], function (
    Base,
    User,
    SessionVars,
    Subjects,
    JobItemView
) {

    return _.patch(Base.CollectionView.extend({

        mixins : [],

        el: '.js-tutor-jobs-region',

        childView: JobItemView,

        childEvents: function() {
            return {
                'job:favourite'      : this.favouriteJob,
                'job:message:create' : this.createMessage,
                'job:message:view'   : this.viewMessage
            }
        },

        favouriteJob: function(param) {
            this.trigger('job:favourite', param.model);
        },

        createMessage: function(param) {
            this.trigger('job:message:create', param.model);
        },

        viewMessage: function(param) {
            this.trigger('job:message:view', param.model);
        }
    }));

});
