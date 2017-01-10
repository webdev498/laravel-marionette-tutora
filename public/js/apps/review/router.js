define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'create'           : laroute.route('review.create', {'tutor' : ':tutor' }),
            'createForStudent' : laroute.route('review.create_for_student'),
            'listReviews'      : laroute.route('admin.tutors.reviews.index', {uuid: ':uuid'}),
            'selectTutor'      : laroute.route('admin.students.review')
        },

        create : function (tutor) {
            this.controller.create({
                'tutor' : tutor
            });
        },

        selectTutor : function (uuid) {
            this.controller.selectTutor({
                uuid: uuid
            });
        },

        listReviews: function (uuid, status) {
            this.controller.listReviews({
                status: status,
                uuid: uuid
            });
        },

        createForStudent : function (tutor, student) {
            this.controller.create({
                'tutor'   : tutor,
                'student' : student
            });
        }

    });

});
