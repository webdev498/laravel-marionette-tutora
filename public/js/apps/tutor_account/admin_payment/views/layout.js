define([
    'base',
    'requirejs-text!apps/tutor_account/admin_payment/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['FormLayout', 'FieldsLayout'],

        template : _.template(template),

        templateHelpers : function () {
            return {
                showIntro : _.indexOf(_.config('cookies'), 'dismissable_tutor_account_payment_introduction') >= 0
            }
        },

        modelEvents : {
            'sync' : 'render'
        },

        ui : {
            'bank'                       : '.js-bank',
            'bank.account_number'        : '.js-bank-account-number',
            'bank.routing_number'        : '.js-bank-sort-code',
            'addresses.billing.line_1'   : '.js-address-line-1',
            'addresses.billing.line_2'   : '.js-address-line-2',
            'addresses.billing.line_3'   : '.js-address-line-3',
            'addresses.billing.postcode' : '.js-address-postcode'
        },

        fields : [
            ['bank', function () { return null; }],
            'bank.account_number',
            'bank.routing_number',
            'addresses.billing.line_1',
            'addresses.billing.line_2',
            'addresses.billing.line_3',
            'addresses.billing.postcode'
        ]

    }));

});
