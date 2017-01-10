define([
    'base',
    'requirejs-text!components/requirements/templates/requirement.html'
], function (
    Base,
    template
) {

    return Base.ItemView.extend({

        tagName : 'li',

        className : 'requirements__item',

        template : _.template(template),

        modelEvents : {
            'change' : 'render'
        }

    });

});
