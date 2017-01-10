define([
    'base',
    'config'
], function (
    Base,
    Config
) {

    var raw_dialogue_routes = Config.get('dialogue_routes');
    var dialogue_routes = {};

    _.each(raw_dialogue_routes, function(raw_dialogue)
    {
        dialogue_routes[raw_dialogue.name] = raw_dialogue.route_string + "(?return=*return_route)";
    });

    var router = {routes: dialogue_routes};

    _.each(dialogue_routes, function(dialogue_route, dialogue_route_name)
    {
        router[dialogue_route_name] = function()
        {
            var return_route = null;
            if(arguments.length >= 2) return_route = arguments[arguments.length - 2];
            this.controller.basic_dialogue({}, dialogue_route_name, return_route);
        }
    });

    return Base.Router.extend(router);
});
