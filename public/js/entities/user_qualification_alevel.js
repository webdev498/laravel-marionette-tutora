define([
    'base'
], function (
    Base
) {

    var College = Base.Model.extend({

        defaults : {
            'subject'        : '',
            'college'        : '',
            'grade'          : '',
            'still_studying' : false
        },

        validation : {
            'college' : [
                {
                    'required' : true,
                    'msg'      : 'The college field is required.'
                }, {
                    'maxLength' : 255,
                    'msg'       : 'The college field must not be greater than 255 characters.'
                }
            ],
            'grade' : [
                {
                    'required' : true,
                    'msg'      : 'The grade field is required.'
                }, {
                    'oneOf' : ['A*', 'A', 'B', 'C', 'D', 'E'],
                    'msg'   : 'The selected grade is invalid.'
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

    var Colleges = Base.Collection.extend({

        url : function () {
            return '/api/users/' + this.user_id + '/qualification_alevels';
        },

        model : College,

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
            return new College(attributes, options);
        },

        collection : function (models, options) {
            return new Colleges(models, options);
        }

    };

});
    