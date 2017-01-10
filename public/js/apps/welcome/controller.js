define([
    'base',
    'apps/welcome/stage1/controller',
    'apps/welcome/stage2/controller'
], function (
    Base,
    WelcomeStage1Controller,
    WelcomeStage2Controller
) {

    return Base.Controller.extend({

        stage1 : function (options) {
            options = _.isObject(options) ? options : {};

            return new WelcomeStage1Controller(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        },

        stage2 : function (options) {
            options = _.isObject(options) ? options : {};

            return new WelcomeStage2Controller(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        }

    });

});
