define([
    'components/requirements/controller'
], function (
    Controller
) {
    return /*App.addInitializer*/ function () {
        var $el = $('.js-requirements');

        if ($el.length > 0) {
            this.addRegions({
                'requirementsRegion' : $el
            });

            return new Controller({
                'app'    : this,
                'region' : this.requirementsRegion
            });
        }
    };
});