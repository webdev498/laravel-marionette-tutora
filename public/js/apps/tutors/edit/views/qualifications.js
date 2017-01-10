define([
    'base',
    'requirejs-text!apps/tutors/edit/templates/dt.html',
    'requirejs-text!apps/tutors/edit/templates/dd.html'
], function (
    Base,
    dtTemplate,
    ddTemplate
) {

    return Base.ItemView.extend({

        template : false,

        initialize : function () {
            this.listenTo(this.model, 'sync', this.qualificationsChanged);
        },

        qualificationsChanged : function () {
            var html = '';

            if (
                this.model.universities.length < 1 &&
                this.model.alevels.length < 1 &&
                this.model.others.length < 1
            ) {
                html += '<dd class="dl__dd">You haven&#39;t added any qualifications yet.</dd>';
            } else {
                if (this.model.universities.length > 0) {
                    html += _.template(dtTemplate, {
                        'heading' : 'University'
                    });

                    this.model.universities.each(function (model) {
                        html += _.template(ddTemplate, {
                            'subject'        : model.get('subject'),
                            'location'       : model.get('university'),
                            'still_studying' : model.get('still_studying'),
                            'level'          : _.lang('qualifications.university.levels.' + model.get('level'))
                        });
                    });
                }

                if (this.model.alevels.length > 0) {
                    html += _.template(dtTemplate, {
                        'heading' : 'College'
                    });

                    this.model.alevels.each(function (model) {
                        html += _.template(ddTemplate, {
                            'subject'        : model.get('subject'),
                            'location'       : model.get('college'),
                            'still_studying' : model.get('still_studying'),
                            'level'          : _.lang('qualifications.alevel.grades.' + model.get('grade'))
                        });
                    });
                }

                if (this.model.others.length > 0) {
                    html += _.template(dtTemplate, {
                        'heading' : 'College'
                    });

                    this.model.others.each(function (model) {
                        html += _.template(ddTemplate, {
                            'subject'        : model.get('subject'),
                            'location'       : model.get('location'),
                            'still_studying' : model.get('still_studying'),
                            'level'          : model.get('grade')
                        });
                    });
                }
            }

            this.$el.html(html);
        }

    });

});
