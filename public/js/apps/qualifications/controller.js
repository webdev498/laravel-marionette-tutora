define([
    'base',
    'apps/qualifications/edit/controller'
], function (
    Base,
    EditController
) {

    return Base.Controller.extend({

        edit : function (options) {
            options = _.isObject(options) ? options : {};

            return new EditController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        }

    });

});
