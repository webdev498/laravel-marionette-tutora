define([
    'base'
], function (
    Base
) {

    var Image = Base.Model.extend({

        urlRoot : '/api/files/images',

        defaults : {
            'uuid'     : null,
            'paths'     : null
        }

    });

    var Images = Base.Collection.extend({

        model : Image,

        url : '/api/files/images'

    });

    return {

        model : function (attributes, options) {
            return new Image(attributes, options);
        },

        collection : function (models, options) {
            return new Images(models, options);
        }

    };

});
