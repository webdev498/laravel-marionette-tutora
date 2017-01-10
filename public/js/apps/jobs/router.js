define([
    'base'
], function (
    Base
) {
    return Base.Router.extend({



        routes : {
            // Admin
            'create_job'         : laroute.route('admin.jobs.create'),
            'create_student_job' : laroute.route('admin.students.jobs.create'),
            'edit_job'           : laroute.route('admin.jobs.details.edit'),

            // Tutor
            'tutor_jobs'         : laroute.route('tutor.jobs.index'),
            'tutor_jobs_intro'   : laroute.route('tutor.jobs.intro'),
            'tutor_job_view'     : laroute.route('tutor.jobs.show')
        },

        create_job : function () {
            this.controller.createJob();
        },

        create_student_job : function (uuid) {
            this.controller.createStudentJob({
                studentUuid : uuid
            });
        },

        edit_job : function (uuid) {
            this.controller.editJob({
                uuid : uuid
            });
        },

        tutor_jobs : function () {
            this.controller.tutorJobs();
        },

        tutor_jobs_intro : function () {
            this.controller.tutorJobs();
        },

        tutor_job_view : function (uuid) {
            this.controller.tutorViewJob({
                uuid : uuid
            });
        }
    });

});
