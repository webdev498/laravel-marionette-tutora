define([
    'backbone.marionette'
], function (
    Marionette
) {

    _.extend(Backbone.Marionette.ItemView.prototype, {

        // http://stackoverflow.com/a/12449916/1284703
        destroy: function(callback) {
            var destroy = Backbone.Marionette.View.prototype.destroy;

            if (this.beforeDestroy) {
                var dfd  = $.Deferred();
                var run  = dfd.resolve

                if(this.beforeDestroy(run) === false) {
                    
                    dfd.done(_.bind(function() {
                        destroy.call(this);
                    }, this));

                    return true;
                }
            }

            destroy.call(this);
        }

    });

    return Marionette.ItemView.extend({});
});
