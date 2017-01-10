define([
    'base',
    'requirejs-text!apps/qts/edit/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template),

        ui : {
            'level' : '.js-qts'
        },

        fields : [
            'level'
        ]

    }));

});
