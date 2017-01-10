define([
    'base',
    'requirejs-text!apps/student_account/payment/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['FormLayout', 'FieldsLayout'],

        template : _.template(template),

        templateHelpers : function () {
            var months = {
                '1'  : 'January',
                '2'  : 'Febuary',
                '3'  : 'March',
                '4'  : 'April',
                '5'  : 'May',
                '6'  : 'June',
                '7'  : 'July',
                '8'  : 'August',
                '9'  : 'September',
                '10' : 'October',
                '11' : 'November',
                '12' : 'December',
            };

            var years = [];
            for (var start = (new Date()).getFullYear(), end = start + 15; start < end; start++) {
                years.push(start);
            }

            return {
                'months'    : months,
                'years'     : years,
                'showIntro' : _.indexOf(
                    _.config('cookies'),
                    'dismissable_student_account_payment_introduction'
                ) >= 0
            };
        },

        modelEvents : {
            'sync' : 'render'
        },

        ui : {
            'card.number'                : '.js-card-number',
            'card.cvc'                   : '.js-card-cvc',
            'card.exp_month'             : '.js-card-exp-month',
            'card.exp_year'              : '.js-card-exp-year',
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
            'addresses.billing.line_1',
            'addresses.billing.line_2',
            'addresses.billing.line_3',
            'addresses.billing.postcode'
        ]

    }));

});
