define([
    'base',
    'apps/autocomplete/subjects/views/item'
], function (
    Base,
    ItemView
) {

    return Base.CollectionView.extend({

        childView : ItemView,

        tagName : 'ul',

        className : 'autocomplete__list',

        childEvents : {
            'fill' : function (view, val) {
                this.trigger('fill', val);
            }
        }

    });

});