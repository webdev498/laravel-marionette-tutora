define([
    'base',
    'requirejs-text!apps/qualifications/edit/templates/composite.html'
], function (
    Base,
    template
) {

    return Base.CompositeView.extend({

        template : _.template(template),

        childViewContainer : '.js-fields',

        ui : {
            'add' : '.js-add'
        },

        events : {
            'click @ui.add' : 'onClickAdd'
        },

        childEvents : {
            'remove' : 'removeModel'
        },

        initialize : function () {
            this.addModelIfEmpty();
        },

        onClickAdd : function (e) {
            if (e.preventDefault) {
                e.preventDefault();
            }

            this.addModel();

            return false;
        },

        getData : function () {
            return this.children.map(function (view) {
                return view.getFieldsData();
            });
        },

        addModel : function () {
            var model = this.collection.model;

            this.collection.add(model);
        },

        addModelIfEmpty : function () {
            if (this.collection.isEmpty()) {
                this.addModel();
            }
        },

        removeModel : function (view, model) {
            this.collection.remove(model);

            this.addModelIfEmpty();
        }

    });

});
