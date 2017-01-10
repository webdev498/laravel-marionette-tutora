define([
    'components/scrolltabs/controller'
], function (
    Controller
) {
    return /*App.addInitializer*/ function () {
        new Controller({
            'app' : this
        });
    };
});
