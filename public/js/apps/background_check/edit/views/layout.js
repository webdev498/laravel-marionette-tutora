define([
    'base',
    'requirejs-text!apps/background_check/edit/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout'],

        className : 'dialogue dialogue--background_check',

        template : _.template(template),

        regions : {
            'dbsRegion'       : '.js-dbs-region',
            'dbsUpdateRegion' : '.js-dbs-update-region'
        },

        ui : {
            'dbs_tab'        : '.js-tab-dbs',
            'dbs_update_tab' : '.js-tab-dbs_update',
            'error'          : '.js-dbs-check-error'
        },

        onShow : function () {
            var tab = this.ui[this.options.tab + '_tab'];

            if (tab && tab.length > 0) {
                tab.click();
            } else {
                this.ui['dbs_tab'].click();
            }
        },

        isDbsTabActive: function() {
            return this.ui.dbs_tab.hasClass('tabs__link--active');
        },

        isDbsUpdateTabActive: function() {
            return this.ui.dbs_update_tab.hasClass('tabs__link--active');
        },

        showErrors : function (errors) {
            if (_.has(errors, 'background_check')) {
                this.ui.error.text(errors.background_check);
                this.ui.error.show();
            }

            _.each(object_undot(errors), function (errorsByViewIndex, key) {
                var region = this[key + 'Region'];
                
                if (region && region.currentView) {
                    region = region.currentView;

                    _.each(errorsByViewIndex, function (value, index) {
                        var view = region.children.findByIndex(index);

                        if (view) {
                            view.showErrors(value);
                        }
                    }, this);
                }
            }, this);

            return true;
        }

    }));

});
