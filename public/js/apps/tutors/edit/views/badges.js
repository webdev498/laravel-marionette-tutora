define([
    'base',
    'requirejs-text!apps/tutors/edit/templates/badges.html'
], function (
    Base,
    template
) {

    return Base.ItemView.extend({

        template : _.template(template),

        templateHelpers : function () {
            var badges = [];

            var qts = this.model.qts;
            if (qts && qts.get('level') !== 'no' && qts.get('level') !== null) {
                badges.push(_.lang('qualifications.teacher_status.levels.' + qts.get('level')));
            }

            var backgrounds = this.model.background_checks;
            if (backgrounds) {
                if (backgrounds.get('background_status') === 'approved') {
                    badges.push(_.lang('background_check.background_check'));
                }
            }

            return {
                'badges' : badges,  
            };
        },

        initialize : function () {
            this.listenTo(this.model.qts, 'sync', this.render);
        }

    });

});
