define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'tutor_create'     : laroute.route('tutor.lessons.create'),
            'tutor_edit'       : laroute.route('tutor.lessons.edit'),
            'tutor_cancel'     : laroute.route('tutor.lessons.cancel'),
            'student_cancel'   : laroute.route('student.lessons.cancel'),
            'student_confirm'  : laroute.route('student.lessons.confirm'),
            'student_pay'      : laroute.route('student.lessons.pay'),
            'admin_create'     : laroute.route('admin.tutors.lessons.create'),
            'admin_cancel'     : laroute.route('admin.lessons.cancel'),
            'admin_edit'       : laroute.route('admin.lessons.edit'),
            'admin_refund'     : laroute.route('admin.lessons.refund'),
            'admin_retry'      : laroute.route('admin.students.lessons.retry')

        },

        tutor_create : function () {
            this.controller.create();
        },

        tutor_edit : function (uuid) {
            this.controller.edit({
                'uuid' : uuid
            });
        },

        tutor_cancel : function (uuid) {
            this.cancel(uuid);
        },

        student_cancel : function (uuid) {
            this.cancel(uuid);
        },

        cancel : function (uuid) {
            this.controller.cancel({
                'uuid' : uuid 
            });
        },

        student_confirm : function (uuid) {
            this.controller.confirm({
                'uuid' : uuid
            });
        },

        student_pay : function (uuid) {
           this.controller.pay({
                'uuid' : uuid
            });
        },

        admin_create : function (uuid) {
           this.controller.createForTutor({
                'uuid' : uuid
            });
        },

        admin_edit : function (uuid) {
           this.controller.edit({
                'uuid' : uuid
            });
        },

        admin_cancel : function (uuid) {
           this.controller.cancel({
                'uuid' : uuid
            });
        },

        admin_refund : function (uuid) {
           this.controller.refund({
                'uuid' : uuid
            });
        },

        admin_retry : function (uuid) {
           this.controller.retry({
                'uuid' : uuid
            });
        }

    });

});
