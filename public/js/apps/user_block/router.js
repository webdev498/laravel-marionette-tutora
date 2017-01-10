define([
    'base'
], function (
    Base
) {
    return Base.Router.extend({

        routes : {
            'tutor_block' : laroute.route('admin.tutors.block'),
            'student_block' : laroute.route('admin.students.block'),
            'tutor_unblock' : laroute.route('admin.tutors.unblock'),
            'student_unblock' : laroute.route('admin.students.unblock')
        },

        tutor_block : function (uuid) {
            this.controller.tutor({
                uuid: uuid,
                op: 'block'
            });
        },

        student_block : function (uuid) {
            this.controller.student({
                uuid: uuid,
                op: 'block'
            });
        },

        tutor_unblock : function (uuid) {
            this.controller.tutor({
                uuid: uuid,
                op: 'unblock'
            });
        },

        student_unblock : function (uuid) {
            this.controller.student({
                uuid: uuid,
                op: 'unblock'
            });
        }

    });

});
