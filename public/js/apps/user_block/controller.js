define([
    'base',
    'apps/user_block/tutor/controller',
    'apps/user_block/student/controller'
], function (
    Base,
    TutorController,
    StudentController
) {

    return Base.Controller.extend({

        tutor : function (options) {

            options = _.isObject(options) ? options : {};

            return new TutorController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        },

        student : function (options) {
            options = _.isObject(options) ? options : {};

            return new StudentController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        }

    });

});
