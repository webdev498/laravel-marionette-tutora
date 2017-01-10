define([
    'base',
    'apps/dialogues/student_job_created/controller'
], function (
    Base,
    StudentJobCreated
) {

    return Base.Controller.extend({

        jobCreated : function (options) {
            options = _.isObject(options) ? options : {};

            return new StudentJobCreated(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        }
    });
});
