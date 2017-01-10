define([
    'base',
    'requirejs-text!apps/toasts/toast/templates/item.html'
], function (
    Base,
    template
) {

    return Base.ItemView.extend({

        tagName : 'li',

        className : 'toasts__item',

        template : _.template(template)

    });

});