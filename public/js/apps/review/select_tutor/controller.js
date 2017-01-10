define([
    'base',
    'entities/user',
    'entities/review',
    'apps/review/select_tutor/views/layout'
], function (
    Base,
    User,
    Review,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController', 'DialogueController'],

        initialize : function (options) {
            this.studentId = options.uuid;
            this.region = options.region;
            this.user   = this.getUser();

            this.view = new LayoutView({
                'collection' : this.user.tutors,
                'model'      : this.user
            });

            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.listenToOnce(this.user.tutors, 'sync', this.showView);
            this.user.tutors.fetch();
        },

        getUser: function() {
            var user = User.model({'uuid' : this.studentId});

            return user;
        },

        showView: function() {
            this.region.show(this.view);
        },

        onDestroy : function () {
            this.app.history.back(-1);
        }

    }));

});
