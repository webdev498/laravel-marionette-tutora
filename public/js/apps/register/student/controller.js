define([
    'base',
    'entities/user',
    'apps/register/student/views/layout'
], function (
    Base,
    User,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController', 'DialogueController'],

        initialize : function (options) {
            this.region = options.region;
            this.user   = new User.model();
            this.view   = new LayoutView({
                'model'            : this.user,
                'attempted_action' : options.attempted_action || 'default'
            });

            this.user.set('action', 'student-registration');

            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.listenTo(this.user, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.user, 'sync', this.onSyncSuccess);
            this.listenTo(this.user, 'error', this.onSyncError);
            this.listenTo(this.user, 'invalid', this.onInvalid);

            this.region.show(this.view);
        },

        save : function (attributes) {
            attributes.account = 'student';
            this.user.save(attributes);
        },

        onSyncSuccess : function (user, json) {
            User.set(json.data);

            if (this.app.callbacks.authenticate) {
                return _.callback(this.app.callbacks.authenticate);  
            }

            return window.location = json.meta.redirect;
        },

        onDestroy : function () {
            var url = laroute.route('home');
            this.app.history.back(url);
        }

    }));

});
