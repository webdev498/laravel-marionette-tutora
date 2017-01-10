define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'edit' : laroute.route('tutor.profile.show', {
                'uuid'    : ':id',
                'section' : 'background_check'
            }) + '(/:tab)',
            'admin_check' : laroute.route('admin.tutors.background_check.index', {
                'uuid'    : ':uuid'
            }),
            'delete'      : laroute.route('admin.tutors.background_check.delete')
        },

        edit : function (id, tab) {
            this.controller.edit({
                'tab' : tab || 'dbs_check'
            });
        },

        admin_check : function (uuid) {
            this.controller.admin_check({
                uuid: uuid
            });
        },

        delete : function (uuid, type) {
            this.controller.delete({
                uuid: uuid,
                type: type
            });
        }

    });

});
