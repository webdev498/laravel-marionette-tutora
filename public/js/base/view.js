define([
    'backbone.marionette'
], function (
    Marionette
) {
    _.extend(Marionette.View.prototype, {

        input : function () {
            var input = {};

            input = _.extend(input, this.inputFromUi());

            input = _.extend(input, this.inputFromChildren());

            input = _.extend(input, this.inputFromRegions());

            return input;
        },

        inputFromUi : function () {
            var input = {};

            if (this.ui) {
                _.each(this.ui, function ($el, key) {
                    switch(true) {
                        case $el.is(':radio'):
                        case $el.is(':checkbox'):
                            input[key] = $el.filter(':checked').val()
                            break;

                        case $el.is('input'):
                        case $el.is('textarea'):
                        case $el.is('select'):
                            input[key] = $el.val();   
                            break;
                    }
                });
            }

            return input;
        },

        inputFromChildren : function () {
            var input = {};

            if (this.children) {
                this.children.each(function (child) {
                    _.extend(input, child.input());
                }, this);
            }

            return input;
        },

        inputFromRegions : function () {
            var input = {};

            if (this.regions) {
                var region;
                _.each(this.regions, function (selector, regionName) {
                    region = this[regionName];

                    if (region.currentView) {
                        _.extend(input, region.currentView.input());
                    }
                }, this);
            }

            return input;
        }
    });

    return Marionette.View.extend({});
});
