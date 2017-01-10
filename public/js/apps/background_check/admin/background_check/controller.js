define([
    'base',
    'dropzone',
    'events',
    'entities/user',
    'apps/background_check/admin/dbs/controller',
    'apps/background_check/admin/dbs_update/controller'
], function (
    Base,
    Dropzone,
    Event,
    User,
    DbsController,
    DbsUpdateController
) {

    return _.patch(Base.Controller.extend({

        initialize : function (options) {
            this.tutorId = options.uuid;

            this.user    = this.getUser();

            _.progress().start();

            this.listenTo(this.user, 'sync', this.triggerSubviews);

            this.user.fetch();
        },

        getUser: function() {
            var user = User.model({'uuid' : this.tutorId});

            return user;
        },

        triggerSubviews: function() {
            _.progress().done();
            this.stopListening(this.user);

            new DbsController({
                'app': this.app,
                'user': this.user,
                'model': this.user.background_checks.dbs,
                'region': this.app.dbsRegion
            });

            new DbsUpdateController({
                'app': this.app,
                'user': this.user,
                'model': this.user.background_checks.dbs_update,
                'region': this.app.dbsUpdateRegion
            });
        }

    }));

});
