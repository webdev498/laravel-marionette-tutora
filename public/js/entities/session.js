define([
    'base'
], function (
    Base
) {

    var Session = Base.Model.extend({

        urlRoot : '/api/sessions',

        defaults : {
            'email'       : null,
            'password'    : null,
            'remember_me' : null
        },

        validation : {
            'email' : [
                {
                    'required' : true,
                    'msg'      : 'The email field is required.'
                }, {
                    'maxLength' : 255,
                    'msg'       : 'The email field must not be greater than 255 characters.'
                }, {
                    'pattern' : 'email',
                    'msg'     : 'The email must be a valid email address.'
                }
            ],
            'password' : [
                {
                    'required' : true,
                    'msg'      : 'The password field is required.'
                }, {
                    'minLength' : 6,
                    'msg'       : 'The password field must be greater than 6 characters.'
                }
            ],
            'remember_me' : [
                {
                    'boolean' : true,
                    'msg'   : 'The remember me field must be true or false.'
                }
            ]
        }

    });

    return {

        model : function (attributes, options) {
            return new Session(attributes, options);
        }

    };

});
