define([
    'base',
    'requirejs-text!components/requirements/templates/layout.html'
], function (
    Base,
    template
) {

    return Base.LayoutView.extend({

        template : _.template(template),

        regions : {
            profileRequirementsRegion : '.js-requirements-profile-region',
            accountRequirementsRegion : '.js-requirements-account-region',
            otherRequirementsRegion   : '.js-requirements-other-region',
        },

        templateHelpers : function () {
            return {
                'percent_completed' : this.percentCompleted()
            };
        },

        collectionEvents : {
            'change' : 'progress',
            'reset'  : 'progress'
        },

        ui : {
            'progress_bar' : '.progress-bar__bar'
        },

        progress : function () {
            this.ui.progress_bar.css({
                'width' : this.percentCompleted() + '%'
            });
        },

        percentCompleted : function () {
            var required  = this.collection.where({'is_optional' : false}).length;
            var completed = this.collection.where({'is_completed' : true, 'is_optional' : false}).length;

            return Math.round(completed / required * 100);
        }

    });

});
