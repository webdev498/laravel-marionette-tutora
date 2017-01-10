define([
    'base'
], function (
    Base
) {

    return Base.ItemView.extend({

        template : false,

        initialize : function () {
            this.listenTo(this.model, 'sync', this.bioChanged);
        },

        bioChanged : function () {
            var bio = this.model.profile.get('bio');

            if (bio) {
                this.$el.html(_.pe(bio));
            }
        }

    });

});
