define([
    'base'
], function (
    Base
) {

    var University = Base.Model.extend({

        defaults : {
            'subject'        : '',
            'university'     : '',
            'level'          : '',
            'still_studying' : false
        },

        validation : function () {
            return {
                'university' : [
                    {
                        'required' : true,
                        'msg'      : 'The university field is required.'
                    }, {
                        'maxLength' : 255,
                        'msg'       : 'The university field must not be greater than 255 characters.'
                    }
                ],
                'level' : [
                    {
                        'required' : true,
                        'msg'      : 'The level field is required.'
                    }, {
                        'oneOf' : ['degree', 'masters', 'phd'],
                        'msg'   : 'The selected level is invalid.'
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
            };
        }

    });

    var Universities = Base.Collection.extend({

        model : University,

        save : function (options) {
            Backbone.sync('create', this, options);
        }

    });

    return {

        model : function (attributes, options) {
            return new University(attributes, options);
        },

        collection : function (models, options) {
            return new Universities(models, options);
        }

    };

});
    