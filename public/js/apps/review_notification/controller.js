define([
    'base',
    'apps/review_notification/stage1/controller'
], function (
    Base,
    ReviewNotificationStage1Controller
) {

    return Base.Controller.extend({

        stage1 : function (options) {
            options = _.isObject(options) ? options : {};

            return new ReviewNotificationStage1Controller(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        }

    });

});
