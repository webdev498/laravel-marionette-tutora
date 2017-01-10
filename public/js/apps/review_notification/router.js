define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'stage1' : laroute.route('tutor.profile.show', {
                'uuid'    : ':id',
                'section' : 'review_notification'
            }),
        },

        stage1: function() {
            this.controller.stage1();
        },
    });
});
