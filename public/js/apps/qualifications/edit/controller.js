define([
    'base',
    'events',
    'entities/user',
    'apps/qualifications/edit/views/layout',
    'apps/qualifications/edit/views/universities',
    'apps/qualifications/edit/views/alevels',
    'apps/qualifications/edit/views/others',
], function (
    Base,
    Event,
    User,
    LayoutView,
    UniversitiesView,
    AlevelsView,
    OthersView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['DialogueController', 'FormController'],

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

            this.listenTo(this.user.qualifications, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.user.qualifications, 'sync', this.onSyncSuccess);
            this.listenTo(this.user.qualifications, 'error', this.onSyncError);
            this.listenTo(this.user.qualifications, 'invalid', this.onInvalid);

            this.region.show(this.view);
        },

        save : function (attributes) {
            Event.trigger('requirement:pending', {
                'section' : 'profile',
                'name'    : 'qualifications'
            });

            _.each([
                'universities',
                'alevels',
                'others'
            ], function (key) {
                var collection = this.user.qualifications[key];
                if (collection) {
                    collection.reset(attributes[key]);
                }
            }, this);

            this.user.qualifications.save();
        },

        onSyncSuccess : function (profile, json) {
            _.toast('Saved!', 'success');
            this.view.destroy();
        },

        onSyncError : function () {
            Event.trigger('requirement:incompleted', {
                'section' : 'profile',
                'name'    : 'qualifications'
            });
        },

        onDestroy : function () {
            var url = laroute.route('tutor.profile.show', {
                'uuid' : this.user.get('uuid')
            });

            this.app.history.back(url);
        },

        onShow : function () {
            this.renderUniversities();  
            this.renderAlevels();
            this.renderOthers();
        },

        renderUniversities : function () {
            var view = new UniversitiesView({
                'collection' : this.user.qualifications.universities
            });

            this.view.universitiesRegion.show(view);
        },

        renderAlevels : function () {
            var view = new AlevelsView({
                'collection' : this.user.qualifications.alevels
            });

            this.view.alevelsRegion.show(view);
        },

        renderOthers : function () {
            var view = new OthersView({
                'collection' : this.user.qualifications.others
            });

            this.view.othersRegion.show(view);
        }

    }));

});
