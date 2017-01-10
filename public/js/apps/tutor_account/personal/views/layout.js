define([
    'base',
    'requirejs-text!apps/tutor_account/personal/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['FormLayout', 'FieldsLayout'],

        template : _.template(template),

        templateHelpers : function () {
            return {
                showIntro : _.indexOf(
                    _.config('cookies'),
                    'dismissable_tutor_account_personal_introduction'
                ) >= 0
            }
        },

        modelEvents : {
            'sync' : 'render'
        },

        ui : {
            'reset_password'             : '.js-reset-password',
            'confirm_reset_password'     : '.js-confirm-reset-password',
            'first_name'                 : '.js-first-name',
            'last_name'                  : '.js-last-name',
            'email'                      : '.js-email',
            'telephone'                  : '.js-telephone',
            'addresses.default.line_1'   : '.js-address-line-1',
            'addresses.default.line_2'   : '.js-address-line-2',
            'addresses.default.line_3'   : '.js-address-line-3',
            'addresses.default.postcode' : '.js-address-postcode'
        },

        fields : [
            'reset_password',
            'confirm_reset_password',
            'first_name',
            'last_name',
            'email',
            'telephone',
            'addresses.default.line_1',
            'addresses.default.line_2',
            'addresses.default.line_3',
            'addresses.default.postcode'
        ]

    }));

});
