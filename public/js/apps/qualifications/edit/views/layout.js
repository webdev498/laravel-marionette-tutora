define([
    'base',
    'requirejs-text!apps/qualifications/edit/templates/layout.html'
], function (
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout'],

        className : 'dialogue dialogue--qualifications',

        template : _.template(template),

        regions : {
            'universitiesRegion' : '.js-universities',
            'alevelsRegion'      : '.js-alevels',
            'othersRegion'       : '.js-others'
        },

        ui : {
            'university_tab' : '.js-tab-university',
            'college_tab'    : '.js-tab-college',
            'other_tab'      : '.js-tab-other',
            'error'          : '.js-qualifications-error'
        },

        onShow : function () {
            var tab = this.ui[this.options.tab + '_tab'];

            if (tab && tab.length > 0) {
                tab.click();
            }
        },

        getData : function () {
            var data = {
                'universities' : [],
                'alevels' : [],
                'others' : []
            };

            _.each(data, function (__, key) {
                var view = this[key + 'Region'].currentView;
                
                if (view && _.isFunction(view.getData)) {
                    data[key] = view.getData();
                }
            }, this)

            return data;
        },

        showErrors : function (errors) {
            if (_.has(errors, 'qualifications')) {
                this.ui.error.text(errors.qualifications);
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
