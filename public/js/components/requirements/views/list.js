define([
    'base',
    'components/requirements/views/requirement',
    'requirejs-text!components/requirements/templates/list.html'
], function (
    Base,
    RequirementView,
    template
) {

    return Base.CompositeView.extend({

        template : _.template(template),

        templateHelpers : function() {

            if(this.options.heading == 'Other')
                this.options.heading = 'Optional'

            return {
                'heading'       : this.options.heading,
                'show'          : this.collection.length > 0,
                'show_heading'   : this.options.heading == 'Optional'
            };
        },

        childViewContainer : '.js-list-region',

        childView : RequirementView

    });

});
