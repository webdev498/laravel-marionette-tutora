define([
    'base',
    'apps/booking/create_for_tutor/controller',
    'apps/booking/create/controller',
    'apps/booking/edit/controller',
    'apps/booking/cancel/controller',
    'apps/booking/confirm/controller',
    'apps/booking/pay/controller',
    'apps/booking/refund/controller'
], function (
    Base,
    CreateForTutorController,
    CreateController,
    EditController,
    CancelController,
    ConfirmController,
    PayController,
    RefundController
) {

    return Base.Controller.extend({

        createForTutor : function (options) {
            options = _.isObject(options) ? options : {};

            return new CreateForTutorController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        },

        create : function (options) {
            options = _.isObject(options) ? options : {};

            return new CreateController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        },

        edit : function (options) {
            options = _.isObject(options) ? options : {};

            return new EditController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options))
        },

        cancel : function (options) {
            options : _.isObject(options) ? options : {};

            return new CancelController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        },

        confirm : function (options) {
            options : _.isObject(options) ? options : {};

            return new ConfirmController(_.extend({
                'app' : this.app
            }, options));
        },

        pay : function (options) {
            options : _.isObject(options) ? options : {};

            return new PayController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        },

        refund : function (options) {
            options : _.isObject(options) ? options : {};

            return new RefundController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        }

    });

});
