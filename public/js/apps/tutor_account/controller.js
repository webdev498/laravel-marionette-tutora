define([
    'base',
    'entities/user',
    'apps/tutor_account/personal/controller',
    'apps/tutor_account/payment/controller',
    'apps/tutor_account/identification/controller',
    'apps/tutor_account/admin_billing/controller'
], function (
    Base,
    User,
    PersonalController,
    PaymentController,
    IdentificationController,
    AdminBillingController
) {

    return Base.Controller.extend({

        personal : function (options) {
            options = _.isObject(options) ? options : {};

            return new PersonalController(_.extend({
                'app'    : this.app,
                'region' : this.app.accountRegion
            }, options));
        },

        payment : function (options) {
            options = _.isObject(options) ? options : {};

            return new PaymentController(_.extend({
                'app'    : this.app,
                'region' : this.app.accountRegion
            }, options));
        },

        identification : function (options) {
            options = _.isObject(options) ? options : {};

            return new IdentificationController(_.extend({
                'app'    : this.app,
                'region' : this.app.accountRegion
            }, options));
        },

        adminBilling : function (options) {
            options = _.isObject(options) ? options : {};

            return new AdminBillingController(_.extend({
                'app'    : this.app
            }, options));
        }

    });

});
