define([
    'base',
    'events',
    'entities/user',
    'entities/subject',
    'apps/subjects/edit/views/layout',
], function (
    Base,
    Event,
    User,
    Subject,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController', 'DialogueController'],

        initialize : function (options) {
            this.region   = options.region;
            this.user     = User.current();
            this.subjects = Subject.collection();
            this.view     = new LayoutView({
                'collection' : this.subjects
            });

            this.listenTo(this.view, 'show', this.onShow);
            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.listenTo(this.user.subjects, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.user.subjects, 'sync', this.onSyncSuccess);
            this.listenTo(this.user.subjects, 'error', this.onSyncError);
            this.listenTo(this.user.subjects, 'invalid', this.onInvalid);

            this.region.show(this.view);
        },

        onShow : function () {
            _.progress().start();

            this.listenToOnce(this.subjects, 'sync', function () {
                _.progress().done();
            });

            this.subjects.fetch();
        },

        save : function (attributes) {
            Event.trigger('requirement:pending', {
                'section' : 'profile',
                'name'    : 'subjects'
            });

            this.user.subjects.reset(attributes.subjects);
            this.user.subjects.save();
        },

        onSyncSuccess : function (profile, json) {
            _.toast('Saved!', 'success');
            this.view.destroy();
        },

        onSyncError : function (model, response) {
            Event.trigger('requirement:incompleted', {
                'section' : 'profile',
                'name'    : 'subjects'
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
