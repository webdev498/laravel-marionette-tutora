define([
    'base'
], function (
    Base
) {

    var Other = Base.Model.extend({

        defaults : {
            'subject'        : '',
            'location'       : '',
            'grade'          : '',
            'still_studying' : false
        },

        validation : {
            'location' : [
                {
                    'required' : true,
                    'msg'      : 'The location field is required.'
                }, {
                    'maxLength' : 255,
                    'msg'       : 'The location field must not be greater than 255 characters.'
                }
            ],
            'grade' : [
                {
                    'required' : true,
                    'msg'      : 'The grade field is required.'
                }, {
                    'maxLength' : 255,
                    'msg'       : 'The grade field must not be greater than 255 characters.'
                }
            ],
            'subject' : [
                {
                    'required' : true,
                    'msg'      : 'The subject field is required.'
                }, {
                    'maxLength' : 255,
                    'msg'       : 'The subject field must not be greater than 255 characters.'
                }
            ],
            'still_studying' : [
                {
                    'boolean' : true,
                    'msg'   : 'The still studying field must be true or false.'
                }
            ]
        }

    });

    var Others = Base.Collection.extend({

        url : function () {
            return '/api/users/' + this.user_id + '/qualification_others';
        },

        model : Other,

        constructor: function(attributes, options) {
            options = options || {};

            this.user_id = options.user_id;

            Backbone.Collection.apply(this, arguments);
        },

        save : function (options) {
            Backbone.sync('create', this, options);
        }

    });

    return {

        model : function (attributes, options) {
            return new Other(attributes, options);
        },

        collection : function (models, options) {
            return new Others(models, options);
        }

    };

});
    