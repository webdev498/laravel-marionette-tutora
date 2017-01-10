define([
    'base',
    'apps/jobs/create_job/controller',
    'apps/jobs/create_student_job/controller',
    'apps/jobs/edit_job/controller',
    'apps/jobs/tutor_view_job/controller',
    'apps/jobs/tutor_jobs/controller'
], function (
    Base,
    CreateJobController,
    CreateStudentJobController,
    EditController,
    TutorViewJobController,
    TutorJobsController
) {

    return Base.Controller.extend({

        createJob : function (options) {
            options = _.isObject(options) ? options : {};

            return new CreateJobController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        },

        createStudentJob : function (options) {
            options = _.isObject(options) ? options : {};

            return new CreateStudentJobController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        },

        editJob : function (options) {
            options = _.isObject(options) ? options : {};

            return new EditController(_.extend({
                'app'    : this.app,
                'region' : this.app.editJobRegion
            }, options));
        },

        tutorViewJob : function (options) {
            options = _.isObject(options) ? options : {};

            return new TutorViewJobController(_.extend({
                'app'    : this.app,
                'region' : this.app.tutorViewJobRegion
            }, options));
        },

        tutorJobs : function (options) {
            options = _.isObject(options) ? options : {};

            return new TutorJobsController(_.extend({
                'app'    : this.app,
                'region' : this.app.tutorJobsRegion
            }, options));
        }
    });
});
