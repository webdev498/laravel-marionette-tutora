define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'tutor' : laroute.route('admin.tutors.delete'),
            'student' : laroute.route('admin.students.delete')
        },

        tutor : function (uuid) {
            this.controller.tutor({
                uuid: uuid
            });
        },

        student : function (uuid) {
            this.controller.student({
                uuid: uuid
            });
        }

    });

});
