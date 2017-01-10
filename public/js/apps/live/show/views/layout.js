define([
    'base',
    'requirejs-text!apps/live/show/templates/new.html',
    'requirejs-text!apps/live/show/templates/submittable.html',
    'requirejs-text!apps/live/show/templates/rejected.html',
    'requirejs-text!apps/live/show/templates/live.html',
    'requirejs-text!apps/live/show/templates/offline.html',
    'requirejs-text!apps/live/show/templates/expired.html',
    'requirejs-text!apps/live/show/templates/pending.html'
], function (
    Base,
    snew,
    submittable,
    rejected,
    live,
    offline,
    expired,
    pending
) {

    return _.patch(Base.LayoutView.extend({

        onBeforeRender : function () {
            var templates = {
                'new'         : snew,
                'submittable' : submittable,
                'rejected'    : rejected,
                'live'        : live,
                'offline'     : offline,
                'expired'     : expired,
                'pending'     : pending
            };

            this.template = _.template(templates[this.model.profile.get('status')]);
        }

    }));

});
