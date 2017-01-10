define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'student_job_created' : laroute.route('student.dashboard.index', {
                'dialogue' : 'student_job_created'
            })
        },

        student_job_created: function() {
            this.controller.jobCreated();
        }
    });

});
