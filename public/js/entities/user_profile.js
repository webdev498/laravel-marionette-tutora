define([
    'base'
], function (
    Base,
    Task
) {

    var Profile = Base.Model.extend({

        defaults : {
            'tagline' : null,
            'rate'    : null
        },

        isNew : function () {
            return false;
        }

    });

    return {

        model : function (attributes, options) {
            return new Profile(attributes, options);
        }

    };

});
