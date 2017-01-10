define([
    'base',
    'entities/user',
    'entities/booking',
    'apps/booking/edit/views/layout'
], function (
    Base,
    User,
    Booking,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController', 'DialogueController'],

        initialize : function (options) {
            this.region = options.region;

            this.booking = Booking.model({
                'uuid' : options.uuid
            });

            _.progress().start();

            this.listenTo(this.booking, 'sync', this.showLayout);
            this.listenTo(this.booking, 'error', this.destroy);

            this.booking.fetch();
        },

        showLayout : function () {
            _.progress().done();

            this.stopListening(this.booking);

            this.view = new LayoutView({
                'model' : this.booking
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
            this.booking.save(attributes);
        },

        onSyncSuccess : function (booking, json) {
            _.toast('Booking updated!', 'success');
            window.location = this.getRedirectUrl();
        },

        onDestroy : function () {
            _.progress().done();
            var url = laroute.route('tutor.lessons.index');
            this.app.history.back(this.getRedirectUrl());
        },

        getRedirectUrl : function () {
            var user = User.current();

            if (user.get('account') === 'tutor') {
                return laroute.route('tutor.lessons.index');
            }

            if (user.get('account') === 'student') {
                return laroute.route('student.lessons.index');
            }
                
            return laroute.route('admin.lessons.index');
        }


    }));

});
