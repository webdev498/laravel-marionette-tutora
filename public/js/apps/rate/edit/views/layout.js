define([
    'base',
    'requirejs-text!apps/rate/edit/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template),

        ui : {
            'profile.rate' : '.js-rate'
        },

        fields : [
            'profile.rate'
        ]

    }));

});
