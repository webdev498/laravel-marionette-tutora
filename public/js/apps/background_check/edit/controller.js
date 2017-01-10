define([
    'base',
    'events',
    'entities/user',
    'apps/background_check/edit/views/layout',
    'apps/background_check/dbs/controller',
    'apps/background_check/dbs_update/controller'
], function (
    Base,
    Event,
    User,
    LayoutView,
    DbsController,
    DbsUpdateController
) {

    return _.patch(Base.Controller.extend({

        mixins : ['DialogueController'],

        initialize : function (options) {
            this.region   = options.region;
            this.user     = User.current();
            this.view     = new LayoutView({
                'tab'   : this.options.tab,
                'model' : this.user
            });

            this.listenTo(this.view, 'show', this.onShow);
            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.region.show(this.view);
        },

        onDestroy : function () {
            var url = laroute.route('tutor.profile.show', {
                'uuid' : this.user.get('uuid')
            });

            this.dbsController.destroy();
            this.dbsUpdateController.destroy();

            this.app.history.back(url);
        },

        onShow : function () {
            this.runDbs();
            this.runDbsUpdate();
        },

        onFormSubmit: function(e) {
            if(this.view.isDbsTabActive()) {
                this.dbsController.view.trigger('form:submit', e);
            }

            if(this.view.isDbsUpdateTabActive()) {
                this.dbsUpdateController.view.trigger('form:submit', e);
            }
        },

        runDbs : function () {
            this.dbsController = new DbsController({
                'app'    : this.app,
                'user'   : this.user,
                'model'  : this.user.background_checks.dbs,
                'region' : this.view.dbsRegion
            });

            this.listenTo(this.dbsController, 'background-saved', this.destroy);
        },

        runDbsUpdate : function () {
            this.dbsUpdateController = new DbsUpdateController({
                'app'    : this.app,
                'user'   : this.user,
                'model'  : this.user.background_checks.dbs_update,
                'region' : this.view.dbsUpdateRegion
            });

            this.listenTo(this.dbsUpdateController, 'background-saved', this.destroy);
        }

    }));

});
