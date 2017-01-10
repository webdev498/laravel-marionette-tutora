define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'edit' : laroute.route('tutor.profile.show', {
                'uuid'    : ':uuid',
                'section' : 'tagline'
            })
        },

        edit : function () {
            this.controller.edit();
        }
       
    });

});