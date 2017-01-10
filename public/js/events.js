define([
    'backbone',
    'pusher',
    'config'
], function (
    Backbone,
    Pusher,
    Config
) {
    var events = _.extend({}, Backbone.Events);

    var uuid = Config.get('user.data.uuid');

    if (uuid) {
        var pusher  = new Pusher(Config.get('services.pusher.key'));
        var channel = pusher.subscribe('user.' + uuid);

        channel.bind('user_requirement_is_pending', function (data) {
            events.trigger('requirement:pending', data.requirement, data);
        });

        channel.bind('user_requirement_was_completed', function (data) {
            // Notify from anywhere.
            
            // if (data.requirement.name == 'identification') {
            //     _.toast('Identification check passed!', 'success');
            // }

            events.trigger('requirement:completed', data.requirement, data);
        });

        channel.bind('user_requirement_is_incomplete', function (data) {
            // Notify from anywhere.
            if (data.requirement.name == 'identification') {
                _.toast('Identification check failed. <a href="' + laroute.route('tutor.account.identification.index') + '">Try again?</a>', 'error', 20000);
            }

            events.trigger('requirement:incompleted', data.requirement, data);
        });

        channel.bind('user_requirements', function (data) {
            events.trigger('requirements:reset', data.requirements, data);
        });

        channel.bind('user_background_checks', function (data) {
            events.trigger('background_checks:reset', data.background_checks, data);
        });
    }

    return events;
});