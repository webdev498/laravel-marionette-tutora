define([
    'base',
    'entities/user',
    'entities/review',
    'apps/review/create/views/layout'
], function (
    Base,
    User,
    Review,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController', 'DialogueController'],

        initialize : function (options) {
            this.region = options.region;
            this.user   = User.current();
            this.review = Review.model({
                'student' : options.student || null
            }, {
                'tutor'   : options.tutor
            });

            this.view = new LayoutView({
                'user' : this.user
            });

            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.listenTo(this.review, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.review, 'sync', this.onSyncSuccess);
            this.listenTo(this.review, 'error', this.onSyncError);
            this.listenTo(this.review, 'invalid', this.onInvalid);

            this.region.show(this.view);
        },

        save : function (attributes) {
            this.review.save(attributes);
        },

        onSyncSuccess : function (review, json) {
            _.toast('Review sent!', 'success');
            this.view.destroy();
        },

        onDestroy : function () {
            var url = laroute.route('student.lessons.index');
            this.app.history.back(url);
        }

    }));

});
