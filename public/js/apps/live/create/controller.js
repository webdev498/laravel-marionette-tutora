define([
    'base',
    'entities/user',
    'apps/live/create/views/layout'
], function (
    Base,
    User,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController', 'DialogueController'],

        initialize : function (options) {
            this.region = options.region;
            this.user   = User.current();
            this.view   = new LayoutView({
                'model' : this.user
            });

            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.listenTo(this.user, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.user, 'sync', this.onSyncSuccess);
            this.listenTo(this.user, 'error', this.onSyncError);
            this.listenTo(this.user, 'invalid', this.onInvalid);
            this.region.show(this.view);
        },

        save : function (attributes) {
            this.user.save({
                'profile' : {
                    'status' : 'submit'
                }
            }, {
                'patch' : true
            });
        },

        onSyncSuccess : function (profile, json) {
            _.toast([
                'Thank you for applying to join the Tutora team. We will contact ',
                'you soon to discuss how we can help to grow your tuition business.',
                '<br><br>',
                "Until then, your profile will appear as 'Pending', but you can ",
                'continue to edit your details.'
            ].join(''), 'success', 15000);

            this.view.destroy();
        },

        onDestroy : function () {
            var url = laroute.route('tutor.profile.show', {
                'uuid' : this.user.get('uuid')
            });

            this.app.history.back(url);
        }

    }));

});
