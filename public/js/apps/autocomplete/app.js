define([
    'apps/autocomplete/controller'
], function (
    Controller
) {
    return /*App.addInitializer*/ function () {
        return new Controller({
            'app' : this
        });
    };
});
