define([
    'base',
    'underscore',
    'requirejs-text!apps/auth/login/templates/layout.html'
], function (
    Base,
    _,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template),

        ui : {
            'email'       : '.js-email',
            'password'    : '.js-password',
            'remember_me' : '.js-remember-me',
        },

        fields : [
            'email',
            'password',
            'remember_me'
        ]

    }));

});
