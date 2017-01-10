define([
    'backbone.marionette'
], function (
    Marionette
) {
    return Backbone.Collection.extend({

        parse : function (response) {
            if (_.has(response, 'meta')) {
                this.meta = response.meta;
            }

            var data = _.has(response, 'data')
                ? response.data
                : response;

            if ( ! this.model) {
                return data;
            } else {
                return _.map(data, function (d) {
                    return this.model.prototype.parse(d);
                }, this);
            }
        },

        toJSON : function () {
            var json = Backbone.Collection.prototype.toJSON.apply(this, arguments);

            if (_.isFunction(this.toJson)) {
                return this.toJson(json);
            }

            return json;
        }

    });
});
