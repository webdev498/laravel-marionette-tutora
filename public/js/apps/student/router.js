define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'request_tutor' : laroute.route('student.request-tutor'),
        },

        request_tutor : function (uuid) {
            this.controller.requestTutor();
        }
    });

});
