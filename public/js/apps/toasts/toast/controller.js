define([
    'base',
    'events',
    'apps/toasts/toast/views/collection',
    'apps/toasts/toast/views/item'
], function (
    Base,
    Events,
    LayoutView
) {

    return Base.Controller.extend({

        initialize : function (options) {
            this.region = options.region;

            this.collection = new Base.Collection();

            this.view = new LayoutView({
                'collection' : this.collection
            });

            this.listenTo(this.view, 'show', this.onShow);

            this.region.show(this.view);
        },

        onShow : function () {
            this.listenTo(Events, 'toast', this.toast);

            var toasts = _.config('toasts');

            if (toasts && ! _.isEmpty(toasts)) {
                _.each(toasts, function (toast) {
                    this.toast(
                        toast.message,
                        toast.severity,
                        toast.duration
                    );
                }, this);
            }
        },

        toast : function (message, severity, duration) {
            severity = severity || 'info';
            duration = duration || 5000;

            this.collection.add(new Base.Model({
                'message'  : message,
                'severity' : severity,
                'duration' : duration
            }));
        }

    });

});