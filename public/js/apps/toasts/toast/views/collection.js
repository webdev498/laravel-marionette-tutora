define([
    'base',
    'apps/toasts/toast/views/item'
], function (
    Base,
    ChildView
) {

    return Base.CollectionView.extend({

        tagName : 'ul',

        className : 'toasts',

        childView : ChildView,

        initialize : function () {
            this.listenTo(this.collection, 'add', function (model) {
                if (this.collection.length > 5) {
                    this.collection.first().destroy();
                }

                setTimeout(function () {
                    model.destroy();
                }, model.get('duration'));
            });
        }

    });

});