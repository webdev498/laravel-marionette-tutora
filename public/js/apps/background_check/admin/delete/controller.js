define([
    'base',
    'entities/background_checks/background_check',
    'apps/background_check/admin/delete/views/layout'
], function (
    Base,
    BackgroundCheck,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController', 'DialogueController'],

        initialize : function (options) {

            this.userUuid   = options.uuid;
            this.type       = options.type;
            this.region     = options.region;
            this.model      = new BackgroundCheck.model({
                userUuid : this.userUuid,
                type     : this.type
            });

            this.view   = new LayoutView({
                'model' : this.model
            });

            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.listenTo(this.model, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.model, 'sync', this.onSyncSuccess);
            this.listenTo(this.model, 'error', this.onSyncError);
            this.listenTo(this.model, 'invalid', this.onInvalid);

            this.region.show(this.view);
        },

        save : function () {
            this.model.remove();
        },

        onSyncSuccess : function(attributes) {
            _.toast('Background check was removed!', 'success');
            this.view.destroy();
        },

        onDestroy : function () {
            var url = laroute.route('admin.tutors.background_check.index', {uuid: this.userUuid});

            this.app.history.navigate(url, {trigger: true});
        }

    }));

});