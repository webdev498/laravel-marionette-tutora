define([
    'base',
    'apps/qualifications/edit/views/composite',
    'apps/qualifications/edit/views/university'
], function (
    Base,
    CompositeView,
    ChildView
) {

    return CompositeView.extend({

        childView : ChildView

    });

});
