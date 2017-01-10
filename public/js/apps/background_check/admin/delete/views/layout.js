define([
    'base',
    'requirejs-text!apps/background_check/admin/delete/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout'],

        template : _.template(template)

    }));

});
