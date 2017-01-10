define([
    'base',
    'dropzone',
    'events',
    'entities/user',
    'apps/tutor_account/admin_identification/controller',
    'apps/tutor_account/admin_payment/controller'
], function (
    Base,
    Dropzone,
    Event,
    User,
    IdentificationController,
    PaymentController
) {

    return _.patch(Base.Controller.extend({

        initialize : function (options) {
            this.tutorId = options.uuid;

            this.user    = this.getUser();

            _.progress().start();

            this.listenTo(this.user, 'sync', this.triggerSubviews);

            this.user.fetch();
        },

        getUser: function() {
            var user = User.model({'uuid' : this.tutorId});

            return user;
        },

        triggerSubviews: function() {
            _.progress().done();
            this.stopListening(this.user);

            new IdentificationController({
                'app': this.app,
                'user': this.user,
                'region': this.app.identificationRegion
            });

            new PaymentController({
                'app': this.app,
                'user': this.user,
                'region': this.app.paymentRegion
            });
        }

    }));

});
