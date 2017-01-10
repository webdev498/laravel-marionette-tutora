define([
    'base'
], function (
    Base
) {

    var MessageLine = Base.Model.extend({

        urlRoot : '',

        idAttribute : 'uuid',

        defaults : {
        },

        constructor: function(attributes, options) {
            attributes = attributes || {};

            if (_.has(attributes, 'data')) {
                attributes = attributes.data;
            }

            return Backbone.Model.apply(this, arguments);
        },

        flagLine : function () {
            var _url = this.url;

            this.url = laroute.route('api.message_lines.flag', {'uuid' : this.get('id')});
            this.save({}, {validate: false});

            this.url = _url;
        }

    });

    var MessageLines = Base.Collection.extend({

        model : MessageLine,

        url : ''

    });

    var currentCollection;

    return {

        model : function (attributes, options) {
            return new MessageLine(attributes, options);
        },

        collection : function (models, options) {
            return new MessageLines(models, options);
        },

        currentCollection : function () {
            if ( ! currentCollection) {
                var models = _.clone(_.config('lines'));
                currentCollection = new MessageLines();

                _.each(models, function(attributes) {
                    var line = new MessageLine(attributes, {
                        'parse' : true
                    });
                    currentCollection.push(line);
                });
            }

            return currentCollection;
        }

    };

});
