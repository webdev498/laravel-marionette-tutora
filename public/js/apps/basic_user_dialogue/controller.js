define([
    'base',
    'apps/basic_user_dialogue/show/controller'
], function (
    Base,
    BasicUserDialogueShowController
) {

    return Base.Controller.extend({

        basic_dialogue : function (options, dialogue_route_name, return_route) {
            options = _.isObject(options) ? options : {};



            return new BasicUserDialogueShowController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion,
                'dialogue_route_name' : dialogue_route_name,
                'return_route' : return_route
            }, options));
        }

    });

});
