define([
    'base',
    'apps/quiz/introduction/controller',
    'apps/quiz/prep/controller',
    'apps/quiz/edit/controller'
], function (
    Base,
    IntroductionController,
    QuizPrepController,
    EditController
) {

    return Base.Controller.extend({

        introduction : function (options) {
            options = _.isObject(options) ? options : {};

            return new IntroductionController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        },

        quiz_prep : function (options, uuid, tab)
        {
            options = _.isObject(options) ? options : {};

            return new QuizPrepController
            (_.extend({
                'tab'               : tab,
                'app'               : this.app,
                'region'            : this.app.dialogueRegion
            }, options));
        },

        edit : function (options) {
            options = _.isObject(options) ? options : {};

            return new EditController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        }

    });

});
