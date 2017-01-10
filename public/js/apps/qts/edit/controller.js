define([
    'base',
    'events',
    'entities/user',
    'apps/qts/edit/views/layout'
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

            this.listenTo(this.user.qts, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.user.qts, 'sync', this.onSyncSuccess);
            this.listenTo(this.user.qts, 'error', this.onSyncError);
            this.listenTo(this.user.qts, 'invalid', this.onInvalid);

            this.region.show(this.view);
        },

        save : function (attributes) {
            Event.trigger('requirement:pending', {
                'section' : 'profile',
                'name'    : 'qualified_teacher_status'
            });

            this.user.qts.save(attributes);
        },

        onSyncSuccess : function (profile, json) {
            _.toast('Saved!', 'success');
            this.view.destroy();
        },

        onSyncError : function () {
            Event.trigger('requirement:incompleted', {
                'section' : 'profile',
                'name'    : 'qualified_teacher_status'
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
