define([
    'base',
    'apps/review/create/controller',
    'apps/review/select_tutor/controller',
    'apps/review/list/controller'
], function (
    Base,
    CreateController,
    SelectTutorController,
    ListReviewsController
) {

    return Base.Controller.extend({

        create : function (options) {
            options = _.isObject(options) ? options : {};

            return new CreateController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        },

        selectTutor : function (options) {
            options = _.isObject(options) ? options : {};

            return new SelectTutorController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        },

        listReviews: function (options) {
            options = _.isObject(options) ? options : {};
            return new ListReviewsController(_.extend({
                'app'    : this.app,
                'region' : this.app.listRegion,
                'dialogueRegion' : this.app.dialogueRegion
            }, options));
        }
    });

});