define([
    'base',
    'entities/session',
    'entities/user',
    'apps/auth/login/views/layout'
], function (
    Base,
    Session,
    User,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController', 'DialogueController'],

        initialize : function (options) {
            this.region  = options.region;
            this.target  = options.target;
            this.session = new Session.model();
            this.view    = new LayoutView({
                'model' : this.session
            });

            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.listenTo(this.session, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.session, 'sync', this.onSyncSuccess);
            this.listenTo(this.session, 'error', this.onSyncError);
            this.listenTo(this.session, 'invalid', this.onInvalid);

            this.region.show(this.view);
        },

        save : function (credentials) {
            this.session.save(credentials);
        },

        onSyncSuccess : function (session, json) {
            User.set(json.data);

            if (this.app.callbacks.authenticate) {
                return _.callback(this.app.callbacks.authenticate);  
            }

            var redirect = json.meta.redirect;

            if(this.target && this.target === 'request-tutor') {
                redirect = laroute.route('student.request-tutor');
            }

            return window.location = redirect;
        },

        onDestroy : function () {
            var url = laroute.route('home');
            this.app.history.back(url);
        }

    }));

});
