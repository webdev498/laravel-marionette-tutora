define([
    'apps/jobs/controller',
    'apps/jobs/router'
], function (
    Controller,
    Router
) {
    return /*App.addInitializer*/ function () {
        var controller = new Controller({
            'app' : this
        });

        this.addRegions({
            'editJobRegion'          : $('.js-edit-job-region'),
            'tutorJobsRegion'        : $('.js-tutor-jobs-region'),
            'initialTutorMessage' : $('.js-job-initial-message-region'),
            'tutorJobReplies'        : $('.js-job-replies-region'),
            'tutorViewJobRegion'     : $('.js-tutor-view-job-region')
        });

        return new Router({
            'controller' : controller
        });
    };
});
