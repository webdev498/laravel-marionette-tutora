define([
    'base',
    'apps/background_check/edit/controller',
    'apps/background_check/admin/background_check/controller',
    'apps/background_check/admin/delete/controller'
], function (
    Base,
    EditController,
    AdminBackgroundCheckController,
    DeleteController
) {

    return Base.Controller.extend({

        edit : function (options) {
            options = _.isObject(options) ? options : {};

            return new EditController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        },

        admin_check: function(options) {
            options = _.isObject(options) ? options : {};

            return new AdminBackgroundCheckController(_.extend({
                'app'    : this.app
            }, options));
        },

        delete: function(options) {
            options = _.isObject(options) ? options : {};

            return new DeleteController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        }

    });

});
