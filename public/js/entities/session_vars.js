define([
    'base',
    'events'
], function (
    Base,
    Event
) {

    var Query = Base.Model.extend({

        defaults : {
            'subject'    : '',
            'location'   : ''
        }

    });

    var SessionVars = Base.Model.extend({

        nested : {
            'models'      : [
                'query'
             ]
        },

        defaults : {
        },

        constructor: function(attributes, options) {
            attributes = attributes || {};

            if (_.has(attributes, 'data')) {
                attributes = attributes.data;
            }

            this.query = new Query();

            return Backbone.Model.apply(this, arguments);
        }

    });

    var current;

    return {

        current : function () {
            if ( ! current) {
                var attributes = _.clone(_.config('session_vars'));
                current = new SessionVars(attributes, {
                    'parse' : true
                });
            }

            return current;
        }

    };

});
