define([
    'base',
    'entities/user',
    'apps/review/list/views/review',
    'apps/review/edit/views/layout',
    'apps/review/delete/views/layout',
    'requirejs-text!apps/review/list/templates/reviews.html'
], function (
    Base,
    User,
    RowView,
    EditLayout,
    DeleteLayout,
    template
) {

    return _.patch(Base.CompositeView.extend({

        childView: RowView,
        childViewOptions: function() {
            return {
                user: this.user,
                deleted: this.deleted
            };
        },
        childViewContainer: "tbody",

        template : _.template(template),

        initialize: function(options) {
            this.deleted = options.deleted;
            this.reviews = options.collection;
            this.user = options.user;
            this.editRegion = options.editRegion;
            this.deleteRegion = options.deleteRegion;
        },

        templateHelpers : function () {
            return {
                'user': this.user
            };
        },

        events: {
            'click .edit-review': 'editReview',
            'click .cancel-review': 'cancelReview'
        },

        editReview: function(e) {
            var id = $(e.currentTarget).data("id");
            this.review = this.collection.get(id);

            this.editView = new EditLayout({
                model : this.review
            });

            this.listenTo(this.editView, 'form:submit', this.save);
            this.listenTo(this.review, 'sync', this.onSyncSuccess);
            this.listenTo(this.editView, 'destroy', this.onDestroy);

            this.editRegion.show(this.editView);
        },

        cancelReview: function (e) {
            var id = $(e.currentTarget).data("id");
            this.review = this.collection.get(id);

            this.deleteView = new DeleteLayout({
                model : this.review
            });

            this.listenTo(this.deleteView, 'form:submit', this.delete);
            this.listenTo(this.deleteView, 'destroy', this.onDestroy);

            this.editRegion.show(this.deleteView);
        },

        save : function (attributes) {
            this.review.save(attributes);
        },

        delete: function (attributes) {
            this.review.destroy(attributes);
            this.deleteView.destroy();
        },

        onSyncSuccess : function (review, json) {
            _.toast('Review updated!', 'success');
            this.editView.destroy();
        },

        onDestroy : function () {
            this.render();
        }

    }));

});
