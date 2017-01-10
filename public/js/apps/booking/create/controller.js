define([
    'base',
    'entities/user',
    'entities/booking',
    'apps/booking/create/views/layout'
], function (
    Base,
    User,
    Booking,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController', 'DialogueController'],

        initialize : function (options) {
            this.region  = options.region;
            this.user    = User.current();
            this.booking = Booking.model(
                {
                    tutor_rate: this.user.get('rate'),
                    trial: _.queryString('trial') | 0
                }
            );

            this.view = new LayoutView({
                'model' : this.booking,
                'user'  : this.user
            });

            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.listenTo(this.booking, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.booking, 'sync', this.onSyncSuccess);
            this.listenTo(this.booking, 'error', this.onSyncError);
            this.listenTo(this.booking, 'invalid', this.onInvalid);

            this.region.show(this.view);
        },

        save : function (attributes) {
            this.booking.student = attributes.student;
            this.booking.subject = attributes.subject;

            this.booking.save(attributes);
        },

        onSyncSuccess : function (booking, json) {
            _.toast('Booking created!', 'success');
            window.location = laroute.route('tutor.lessons.index');
        },

        onDestroy : function () {
            var url = laroute.route('tutor.lessons.index');
            this.app.history.back(url);
        }

    }));

});
