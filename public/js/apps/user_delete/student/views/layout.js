define([
    'base',
    'requirejs-text!apps/user_delete/student/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout'],

        template : _.template(template)

    }));

});
