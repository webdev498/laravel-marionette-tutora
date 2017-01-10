define([
    'base',
    'entities/user',
    'entities/booking',
    'apps/booking/create_for_tutor/views/layout'
], function (
    Base,
    User,
    Booking,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController', 'DialogueController'],

        initialize : function (options) {
            this.region   = options.region;
            this.tutorId  = options.uuid || null;
            this.user     = this.getUser();

            _.progress().start();

            this.listenTo(this.user, 'sync', this.showLayout);
            this.listenTo(this.user, 'error', this.destroy);

            this.user.fetch();
        },

        getUser: function() {
            var user = User.model({'uuid' : this.tutorId});

            return user;
        },

        showLayout: function() {

            this.booking  = Booking.model({
                tutor_rate: this.user.get('rate'),
                trial: _.queryString('trial') | 0
            });

            _.progress().done();
            this.stopListening(this.booking);

            this.view = new LayoutView({
                'model'    : this.booking,
                'user'     : this.user
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

            this.booking.saveForTutor(this.tutorId, attributes);
        },

        onSyncSuccess : function (booking, json) {
            _.toast('Booking created!', 'success');
            this.view.destroy();
        },

        onDestroy : function () {
            var url = laroute.route('admin.tutors.lessons.index', {'uuid' : this.tutorId});

            this.app.history.back(url);
        }

    }));

});
