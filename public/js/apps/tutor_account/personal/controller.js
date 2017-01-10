define([
    'base',
    'entities/user',
    'apps/tutor_account/personal/views/layout'
], function (
    Base,
    User,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController'],

        initialize : function (options) {
            this.user = User.current();

            this.view = new LayoutView({
                'model' : this.user
            });

            this.listenTo(this.view, 'render', this.onRender);
            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.listenTo(this.user, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.user, 'sync', this.onSyncSuccess);
            this.listenTo(this.user, 'error', this.onSyncError);
            this.listenTo(this.user, 'invalid', this.onInvalid);

            this.region.show(this.view);
        },

        save : function (attributes) {
            this.user.save(object_undot(attributes), {
                'patch' : true
            });
        },

        onSyncSuccess : function (profile, json) {
            _.toast('Saved!', 'success');

            this.view.enableSubmit();
        }

    }));

});
