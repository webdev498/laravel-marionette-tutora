define([
    'components/autocomplete/controller'
], function (
    Controller
) {
    return /*App.addInitializer*/ function () {
        var $els = $('.js-autocomplete');

        $els.each(function (i, el) {
            new Controller({
                'app' : this,
                'el'  : el
            });
        });
    };
});
