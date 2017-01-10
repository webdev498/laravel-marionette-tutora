define([
    'base'
], function (
    Base
) {
    return _.patch(Base.ItemView.extend({

        mixins : ['FormLayout', 'FieldsLayout'],

        template : false, 

        ui : {
            'card.number'                : '.js-card-number',
            'card.cvc'                   : '.js-card-cvc',
            'card.exp_month'             : '.js-card-expiry-month',
            'card.exp_year'              : '.js-card-expiry-year',
            'card.exists'                : '.js-card-exists',
            'addresses.billing.line_1'   : '.js-address-line-1',
            'addresses.billing.line_2'   : '.js-address-line-2',
            'addresses.billing.line_3'   : '.js-address-line-3',
            'addresses.billing.postcode' : '.js-address-postcode'
        },

        fields : [
            'card.number',
            'card.cvc',
            'card.exp_month',
            'card.exp_year',
            [
                'card.exists',
                function () {
                    return _.val(this.ui['card.exists']) === "1" ? true : false;
                }
            ],
            'addresses.billing.line_1',
            'addresses.billing.line_2',
            'addresses.billing.line_3',
            'addresses.billing.postcode'
        ],

        initialize : function () {
            this.bindUIElements();
        }

    }));

});