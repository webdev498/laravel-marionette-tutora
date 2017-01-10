define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'edit' : laroute.route('tutor.profile.show', {
                'uuid'    : ':id',
                'section' : 'qualifications'
            }) + '(/:tab)'
        },

        edit : function (id, tab) {
            this.controller.edit({
                'tab' : tab || 'university'
            });
        }

    });

});
