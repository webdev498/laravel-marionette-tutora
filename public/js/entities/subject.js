define([
    'base'
], function (
    Base
) {

    var Subject = Base.Model.extend({

        urlRoot : '/api/subjects',

        defaults : {
            'name'     : null,
            'path'     : null,
            'depth'    : null,
            'children' : null
        },

        toJson : function (json) {
            if (_.has(json, 'children') && _.isArray(json.children)) {
                json.children = _.map(json.children, function (child) {
                    return child.toJSON();
                });
            }

            return json;
        }

    });

    var Subjects = Base.Collection.extend({

        model : Subject,

        url : '/api/subjects',

        parse : function (response) {
            if (_.has(response, 'data')) {
                data = response.data;   
            } else {
                data = response;
            }

            if ( ! _.isArray(data)) {
                return data;
            }

            return _.map(data, function (d) {
                if (_.has(d, 'children') && _.isArray(d.children)) {
                    d.children = this.parse(d.children);
                }

                return new Subject(d);
            }, this);
        },

        save : function (options) {
            options = _.extend(options || {}, {
                'attrs' : {
                    'subjects' : this.toJSON()
                }
            });

            Backbone.sync('create', this, _.extend({

                'success' : _.bind(function (response) {
                    response = this.parse(response);

                    this.reset(response, {
                        'silent' : true
                    });

                    this.trigger('sync', this, response, options);
                }, this),

                'error' : _.bind(function (response) {
                    this.trigger('error', this, response, options);
                }, this)

            }, options));
        }
 
    });

    return {

        model : function (attributes, options) {
            return new Subject(attributes, options);
        },

        collection : function (models, options) {
            return new Subjects(models, options);
        }

    };

});
