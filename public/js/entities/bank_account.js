define([
    'base'
], function (
    Base
) {

    var BankAccount = Base.Model.extend({

        defaults : {
            'account_number' : null,
            'routing_number' : null,
            'country'        : 'GB',
            'currency'       : 'GBP'
        },

        validation : {
            'account_number' : [
                {
                    'required' : true,
                    'msg'      : 'The bank account field is required.'
                }
            ],
            'routing_number' : [
                {
                    'required' : true,
                    'msg'      : 'The sort code field is required.'
                }
            ]
        }

    });

    return {

        model : function (attributes, options) {
            return new BankAccount(attributes, options);
        }

    };

});
