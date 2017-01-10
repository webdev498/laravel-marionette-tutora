define([
    'base'
], function (
    Base
) {

    var Address = Base.Model.extend({

        defaults : {
            'line_1'    : null,
            'line_2'    : null,
            'line_3'    : null,
            'postcode'  : null,
            'latitude'  : null,
            'longitude' : null
        }

    });

    var Addresses = Base.Model.extend({

        nested : {
            'models' : ['default', 'billing']
        },

        constructor: function (attrs, options) {
            options = options || {};

            _.each(this.nested.models, function (name) {
                this[name]      = new Address();
                this[name].url  = this.url + '/' + name;
            }, this);

            Base.Model.apply(this, arguments);
        },

        clear : function (options) {
            _.each(this.nested.models, function (name) {
                this[name]
                    .clear(options)
                    .set(
                        _.clone(this[name].defaults),
                        { 'silent' : true  }
                    );
            }, this);

            return this;
        },

        set : function (attrs, options) {
            _.each(this.nested.models, function (name) {
                this[name].set(attrs[name], options);
            }, this);

            return this;
        }

    });

    return {

        model : function (attrs, options) {
            return new Address(attrs, options);
        },

        models : function (attrs, options) {
            return new Addresses(attrs, options);
        }

    };

});
    