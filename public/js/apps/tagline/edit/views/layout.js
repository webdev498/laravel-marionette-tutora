define([
    'base',
    'requirejs-text!apps/tagline/edit/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template),

        ui : {
            'profile.tagline' : '.js-tagline'
        },

        fields : [
            'profile.tagline'
        ]

    }));

});
