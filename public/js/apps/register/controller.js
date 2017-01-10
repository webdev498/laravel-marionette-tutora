define([
    'base',
    'events',
    'apps/register/student/controller',
    'apps/register/tutor/controller',
], function (
    Base,
    Event,
    Student,
    Tutor,
    Login
) {

    return Base.Controller.extend({

        initialize : function () {
            this.listenTo(Event, 'register.student', this.student);
        },

        student : function (options) {
            options = _.isObject(options) ? options : {};

            return new Student(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        },

        tutor : function (options) {
            options = _.isObject(options) ? options : {};

            return new Tutor(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        }

    });

});
