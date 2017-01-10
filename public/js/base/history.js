define([
    'backbone.marionette'
], function(
    Marionette
) {
    var Model = Backbone.Model.extend({

        defaults: {
            'fragment': ''
        }

    });

    var Collection = Backbone.Collection.extend({

        model: Model

    });

    return Marionette.Controller.extend({

        initialize: function(options) {
            this.app = options.app;

            this.history = new Collection();
        },

        start: function(options) {
            Backbone.history.start(options);

            this.updateHistory();
        },

        navigate: function(fragment, options) {
            Backbone.history.navigate(fragment, options);
            this.updateHistory();
        },

        back : function (fallback) {
            var one = this.history.at(1);

            this.navigate(one ? one.get('fragment') : fallback);
        },

        navigateToRoute: function(route, parameters, options) {
            var fragment = laroute.route(route, parameters);

            this.navigate(fragment, options);
        },

        updateHistory: function() {
            this.history.unshift(this.getFragment());
        },

        getFragment: function() {
            var fragment;

            if (Backbone.history.fragment) {
                fragment = Backbone.history.getFragment();
            } else {
                fragment = Backbone.history.getHash();
            }

            return new Model({
                'fragment': fragment
            });
        }

    });

});