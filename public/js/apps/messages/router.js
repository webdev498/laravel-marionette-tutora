define([
    'base'
], function (
    Base
) {

    return Base.Router.extend({

        routes : {
            'create'        : laroute.route('message.create', {'uuid' : ':uuid' }),
            'tutor'         : laroute.route('tutor.messages.show', { 'id' : ':id' }),
            'student'       : laroute.route('student.messages.show', { 'id' : ':id' }),
            'admin'         : laroute.route('admin.relationships.messages.show', {'id' : ':id'}),
            'adminIndex'    : laroute.route('admin.messages.index')
        },

        create : function (uuid) {
            this.controller.create({
                'uuid' : uuid
            });
        },

        tutor : function (id) {
            this.controller.show({
                'id' : id
            });
        },

        student : function (id) {
            this.controller.show({
                'id' : id
            });
        },

        admin : function (id) {
            this.controller.show({
                'id' : id
            });
        },

        adminIndex : function (id) {
            this.controller.adminIndex({
                'id' : id
            });
        }

    });

});
