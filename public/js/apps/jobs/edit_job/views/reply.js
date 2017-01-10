define([
    'base',
    'entities/user',
    'requirejs-text!apps/jobs/edit_job/templates/reply.html'
], function (
    Base,
    User,
    template
) {

    return _.patch(Base.ItemView.extend({

        tagName : 'tr',

        template : _.template(template),

        templateHelpers : function () {
            return {
                'tutorLink' : laroute.route('tutor.profile.show', {'uuid' : this.model.get('tutor_uuid')})
            };
        }

    }));

});
