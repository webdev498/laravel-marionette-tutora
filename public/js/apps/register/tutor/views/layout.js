define([
    'base',
    'requirejs-text!apps/register/tutor/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template),

        ui : {
            'a'          : 'a[data-js]',
            'first_name' : '.js-first-name',
            'last_name'  : '.js-last-name',
            'email'      : '.js-email',
            'telephone'  : '.js-telephone',
            'password'   : '.js-password'
        },

        fields : [
            'first_name',
            'last_name',
            'email',
            'telephone',
            'password'
        ],

        events : {
            'click @ui.a' : 'onClickA'
        },

        onClickA : function (e) {
            this.destroy();
        }

    }));

});
