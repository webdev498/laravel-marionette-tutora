define([
    'base',
    'events',
    'entities/user',
    'apps/tagline/edit/views/layout'
], function (
    Base,
    Event,
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
            Event.trigger('requirement:pending', {
                'section' : 'profile',
                'name'    : 'tagline'
            });

            this.user.save(object_undot(attributes), {
                'patch' : true
            });
        },

        onSyncSuccess : function (profile, json) {
            _.toast('Saved!', 'success');

            this.view.destroy();
        },

        onSyncError : function (user, response) {
            Event.trigger('requirement:incompleted', {
                'section' : 'profile',
                'name'    : 'tagline'
            });
        },

        onDestroy : function () {
            var url = laroute.route('tutor.profile.show', {
                'uuid' : this.user.get('uuid')
            });

            this.app.history.back(url);
        }

    }));

});