define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'student' : laroute.route('register.student'),
            'tutor'   : laroute.route('register.tutor')
        },

        student : function () {
            this.controller.student();
        },

        tutor : function () {
            this.controller.tutor();
        }

    });

});
