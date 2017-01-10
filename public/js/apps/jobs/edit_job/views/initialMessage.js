define([
    'base',
    'requirejs-text!apps/jobs/edit_job/templates/initialMessage.html'
], function (
    Base,
    template
) {

    return _.patch(Base.ItemView.extend({

        template : _.template(template),

        templateHelpers : function () {
            return {
                'tutorLink' : laroute.route('tutor.profile.show', {'uuid' : this.model.get('tutor_uuid')})
            };
        }

    }));

});
