define([
    'base',
    'apps/live/show/controller',
    'apps/live/create/controller',
    'apps/live/edit/controller',
    'apps/live/destroy/controller'
], function (
    Base,
    ShowController,
    CreateController,
    EditController,
    DestroyController
) {

    return Base.Controller.extend({

        show : function (options) {
            options = _.isObject(options) ? options : {};

            return new ShowController(_.extend({
                'app'    : this.app,
                'region' : this.app.liveRegion
            }));
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
            // console.log('here');
            return new EditController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        },

        destroy : function (options) {
            options = _.isObject(options) ? options : {};

            return new DestroyController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        }

    });

});
