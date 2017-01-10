define([
    'base',
    'entities/image'
], function (
    Base,
    Image
) {

    var Dbs = Base.Model.extend({

        nested : {
            'models' : [
                'image'
            ]
        },

        idAttribute : 'uuid',

        defaults : {
            'uuid'           : null,
            'status'         : '',
            'admin_status'   : '',
            'issued_at'      : '',
            'rejected_for'   : null,
            'reject_comment' : null
        },

        constructor: function(attributes, options) {
            this.image = Image.model();

            Backbone.Model.apply(this, arguments);
        },

        isRejectedStatus: function(value) {
            return parseInt(value) === 3;
        },

        isCustomRejectReason: function(value) {
            return parseInt(value) === 5;
        }

    });

    return {

        model : function (attributes, options) {
            return new Dbs(attributes, options);
        }

    };

});