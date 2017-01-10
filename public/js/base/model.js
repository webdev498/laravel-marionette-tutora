define([
    'backbone.marionette'
], function (
    Marionette
) {
    return Backbone.Model.extend({

        nested : {
            'models' : [],
            'collections' : []  
        },

        sync : function(method, model, options) {
            options.beforeSend = function(xhr){
                xhr.setRequestHeader(
                    'X-XSRF-TOKEN',
                    _.cookie('XSRF-TOKEN')
                );
            };

            if (_.config('environment') === 'testing') {
                var spoof = false;

                switch(method.toLowerCase()) {
                    case 'patch':
                        method  = 'create';
                        spoof   = 'patch';
                        break;
                }

                if (spoof) {
                    options.attrs._method = spoof;
                }
            }

            return Backbone.Model.prototype.sync.call(this, method, model, options);
        },

        parse : function (response) {
            if (_.has(response, 'meta')) {
                this.meta = response.meta;
            }

            var data = _.has(response, 'data')
                ? response.data
                : response;

            if (_.has(data, 'private')) {
                var private = _.has(data.private, 'data')
                    ? data.private.data
                    : data.private;

                data = _.extend(data, private);

                delete data.private;
            }

            if (_.has(this.nested, 'models')) {
                _.each(this.nested.models, function (key) {
                    data = this.parseModel(data, key);
                }, this);
            }

            if (_.has(this.nested, 'collections')) {
                _.each(this.nested.collections, function (key) {
                    data = this.parseCollection(data, key);
                }, this);
            }

            return data;
        },

        parseModel : function (data, key) {
            if (_.has(data, key)) {
                this[key]
                    .clear({
                        'silent' : true
                    })
                    .set(
                        // { 'parse' : true }
                        this[key].parse(data[key])
                    );

                this[key].trigger('parsed');

                delete data[key];
            }

            return data;
        },

        parseCollection : function (data, key) {
            if (_.has(data, key)) {
                this[key].reset(
                    // { 'parse' : true }
                    this[key].parse(data[key])
                );

                this[key].trigger('parsed');

                delete data[key];
            }

            return data;
        },

        toJSON : function () {
            var json = Backbone.Model.prototype.toJSON.apply(this, arguments);
            
            if (_.isFunction(this.toJson)) {
                return this.toJson(json);
            }

            return json;
        },

        toJson : function (json) {
            if (_.has(this.nested, 'models')) {
                _.each(this.nested.models, function (key) {
                    json[key] = this[key].toJSON();
                }, this);
            }
            
            if (_.has(this.nested, 'collections')) {
                _.each(this.nested.collections, function (key) {
                    json[key] = this[key].toJSON();
                }, this);
            }

            return json;
        }

    });
});
