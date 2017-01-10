define([
    'base'
], function (
    Base
) {

    var Location = Base.Model.extend({

        urlRoot : '/api/location',

        idAttribute : 'uuid',

        defaults : {
            'postcode' : null
        },

        parse : function (response) {
            if ( ! _.has(response, 'data')) {
                return response;
            }

            return response.data;
        }

    });

    return {

        model : function (attributes, options) {
            return new Location(attributes, options);
        }

    };

});
