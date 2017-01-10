define([
    'base',
    'apps/quiz/edit/views/question'
], function (
    Base,
    ChildView
) {

    return Base.CollectionView.extend({

        childView : ChildView

    });

});
