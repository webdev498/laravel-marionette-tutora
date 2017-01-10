define([
    'base',
    'requirejs-text!apps/review/list/templates/review.html'
], function (
    Base,
    template
) {

    return _.patch(Base.ItemView.extend({

        tagName : 'tr',

        template : _.template(template),

        initialize: function(options) {
            this.user = options.user;
            this.deleted = options.deleted;
        },

        templateHelpers : function () {
            return {
                user: this.user,
                deleted: this.deleted
            };
        }

    }));

});
