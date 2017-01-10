define([
    'base',
    'requirejs-text!apps/tutors/edit/templates/distance.html'
], function (
    Base,
    template
) {

    return Base.ItemView.extend({

        template : _.template(template),

        initialize : function () {
            this.listenTo(this.model.profile, 'parsed', this.render);
        }

    });

});
