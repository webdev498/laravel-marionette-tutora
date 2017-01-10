define([
    'base',
    'apps/student_account/personal/controller',
    'apps/student_account/payment/controller',
    'apps/student_account/admin_payment/controller'
], function (
    Base,
    PersonalController,
    PaymentController,
    AdminPaymentController
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

        adminPayment : function (options) {
            options = _.isObject(options) ? options : {};

            return new AdminPaymentController(_.extend({
                'app'    : this.app,
                'region' : this.app.accountRegion
            }, options));
        }
    });
});
