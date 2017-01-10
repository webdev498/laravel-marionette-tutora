define([
    'base',
    'events',
    'entities/user',
    'apps/live/show/views/layout'
], function (
    Base,
    Event,
    User,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        initialize : function (options) {
            this.region = options.region;
            this.user   = User.current();
            this.view   = new LayoutView({
                'model' : this.user
            });

            this.listenTo(this.user.profile, 'change:status', function () {
                if (this.user.profile.get('status') !== 'review') {
                    this.view.render();
                }
            });

            this.listenTo(Event, 'requirement:completed', function (requirement, data) {
                this.user.profile.set('status', data.profile.status);
                this.view.render();
            });

            this.region.show(this.view);
        }

    }));

});
