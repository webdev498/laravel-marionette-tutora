define([
    'base'
], function (
    Base
) {

    var TeacherStatus = Base.Model.extend({

        defaults : {
            'level' : null
        },

        isNew : function () {
            return false;
        }

    });

    return {

        model : function (attributes, options) {
            return new TeacherStatus(attributes, options);
        }

    };

});
    