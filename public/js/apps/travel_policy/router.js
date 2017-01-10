define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'edit' : laroute.route('tutor.profile.show', {
                'uuid'    : ':uuid',
                'section' : 'travel_policy'
            })
        },

        edit : function () {
            this.controller.edit();
        }

    });

});
