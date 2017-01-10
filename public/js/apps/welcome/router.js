define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'stage1' : laroute.route('tutor.profile.show', {
                'uuid'    : ':id',
                'section' : 'welcome'
            }),
            'stage2' : laroute.route('tutor.profile.show', {
                'uuid'    : ':id',
                'section' : 'welcome'
            }) + '/2'
        },

        stage1: function() {
            this.controller.stage1();
        },

        stage2 : function () {
            this.controller.stage2();
        }
    });
});
