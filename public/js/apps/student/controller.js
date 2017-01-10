define([
    'base',
    'apps/student/request_tutor/controller'
], function (
    Base,
    RequestTutorController
) {

    return Base.Controller.extend({

        requestTutor : function (options) {
            options = _.isObject(options) ? options : {};

            return new RequestTutorController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        }
    });
});
