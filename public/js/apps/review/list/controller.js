define([
    'base',
    'entities/user',
    'entities/review',
    'apps/review/list/views/reviews'
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
            this.uuid = options.uuid;
            this.dialogueRegion = options.dialogueRegion;
            this.deleted = (options.status != null);

            this.reviews = Review.collection();

            _.progress().start();

            this.listenToOnce(this.reviews, 'sync', this.renderLayout);
            this.reviews.fetch({url: laroute.route('api.reviews', {'uuid' : this.uuid, 'status': this.deleted})});
        },

        renderLayout: function () {
            this.view = new LayoutView({
                'collection' : this.reviews,
                'user' : this.user,
                'editRegion' : this.dialogueRegion,
                'deleteRegion' : this.dialogueRegion,
                'deleted' : this.deleted
            });

            _.progress().done();

            this.region.show(this.view);
        }
    }));

});
