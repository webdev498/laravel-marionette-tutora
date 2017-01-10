define([
    'base'
], function (
    Base
) {

    var BankCard = Base.Model.extend({

        defaults : {
            'number'    : null,
            'cvc'       : null,
            'exp_month' : null,
            'exp_year'  : null
        },

        validation : {
            'number' : [
                {
                    'required' : true,
                    'msg'      : 'The card number field is required.'
                }
            ],

            'cvc' : [
                {
                    'required' : true,
                    'msg'      : 'The card cvc field is required.'
                }
            ],

            'exp_month' : [
                {
                    'required' : true,
                    'msg'      : 'The card expiry month field is required.'
                }
            ],

            'exp_year' : [
                {
                    'required' : true,
                    'msg'      : 'The card expiry year field is required.'
                }
            ]
        }

    });

    return {

        model : function (attributes, options) {
            return new BankCard(attributes, options);
        }

    };

});
