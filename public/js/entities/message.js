define([
    'base',
    'entities/user'
], function (
    Base,
    User
) {

    var user = User.current();

    var Message = Base.Model.extend({

        urlRoot : '/api/messages',

        idAttribute : 'uuid',

        defaults : {
            'body'   : null,
            'action' : 'none',
            'to'     : null
        },

        validation : {
            'body' : [
                {
                    'required' : true,
                    'msg'      : 'Please enter a message to send.'
                },
                {
                    'minLength' : 50,
                    'msg'       : 'Please write at least 50 characters. The more you can write, the more likely you are to receive a reply.'
                }
            ],
            'to' : [
                {
                    'required' : true,
                    'msg'      : 'A recipient is required.'
                }
            ],
            'subject_name' : [
                {
                    'required' : function () {
                        var isRegister    = user.isNew() && this.get('action') === 'register';
                        var isMessageForm = this.get('action') === 'message';

                        return isRegister || isMessageForm;
                    },
                    'msg'      : 'Please enter a subject.'
                }
            ],
            'location_postcode' : [
                {
                    'required' : function () {
                        var isRegister  = user.isNew() && this.get('action') === 'register';
                        var isMessageForm = this.get('action') === 'message';

                        return isRegister || isMessageForm;
                    },
                    'msg'      : 'Please enter a location.'
                }
            ],
            'register.first_name' : [
                {
                    'required' : function () {
                        return user.isNew() && this.get('action') === 'register';
                    },
                    'msg'      : 'The first name field is required.'
                }
            ],
            'register.last_name' : [
                {
                    'required' : function () {
                        return user.isNew() && this.get('action') === 'register';
                    },
                    'msg'      : 'The last name field is required.'
                }
            ],
            'register.email' : [
                {
                    'required' : function () {
                        return user.isNew() && this.get('action') === 'register';
                    },
                    'msg'      : 'The email field is required.'
                }
            ],
            'register.telephone' : [
                {
                    'required' : function () {
                        return user.isNew() && this.get('action') === 'register';
                    },
                    'msg'      : 'The telephone field is required.'
                }
            ],
            'register.password' : [
                {
                    'required' : function () {
                        return user.isNew() && this.get('action') === 'register';
                    },
                    'msg'      : 'The password field is required.'
                }
            ],
            'login.email' : [
                {
                    'required' : function () {
                        return user.isNew() && this.get('action') === 'login';
                    },
                    'msg'      : 'The email field is required.'
                }
            ],
            'login.password' : [
                {
                    'required' : function () {
                        return user.isNew() && this.get('action') === 'login';
                    },
                    'msg'      : 'The password field is required.'
                }
            ],
            'login.remember me' : [
                {
                    'required' : function () {
                        return user.isNew() && this.get('action') === 'login';
                    },
                    'msg'      : 'The remember me field is required.'
                }
            ]
        },

        parse : function (response) {
            if ( ! _.has(response, 'data')) {
                return response;
            }

            return response.data;
        },

        saveForJob : function (jobUuid, attributes) {
            var _url = this.url;

            this.url = laroute.route('api.jobs.message.create', {'uuid' : jobUuid});
            this.save(attributes);

            this.url = _url;
        }

    });

    return {

        model : function (attributes, options) {
            return new Message(attributes, options);
        }

    };

});
