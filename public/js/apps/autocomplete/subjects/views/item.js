define([
    'base'
], function (
    Base
) {

    return Base.ItemView.extend({

        template : _.template('<%= title %>'),

        tagName : 'li',

        className : 'autocomplete__item',

        events : {
            'click' : 'onClick'
        },

        onClick : function () {
            this.trigger('fill', this.model.get('title'));
        }

    });

});