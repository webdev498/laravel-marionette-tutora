define([
    'base',
    'entities/user',
    'apps/user_block/student/views/layout'
], function (
    Base,
    User,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController', 'DialogueController'],

        initialize : function (options) {

            this.studentId = options.uuid;
            this.region = options.region;
            this.user   = this.getUser();
            this.op = options.op;

            // set the operation (block/unblock)
            this.user.attributes.op = options.op;

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

        getUser: function() {
            var user = User.model({'uuid' : this.studentId});

            return user;
        },

        save : function (attributes) {
            this.user.toggleBlock();
        },

        onSyncSuccess : function(attributes) {
            _.toast('Student '+this.op+'ed!', 'success');
            this.view.destroy();
            setTimeout(function () {location.reload()}, 1000);
        },

        onDestroy : function () {
            this.app.history.back(-1);
        }

    }));

});