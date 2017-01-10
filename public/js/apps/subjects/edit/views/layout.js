define([
    'base',
    'entities/user',
    'requirejs-text!apps/subjects/edit/templates/layout.html'
], function (
    Base,
    User,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['DialogueLayout', 'FormLayout', 'FieldsLayout'],

        template : _.template(template),

        templateHelpers : function () {
            var user = User.current();

            return {
                'collection' : this.collection.toJSON(),
                'userHasSubject' : function (id) {
                    return ! _.isUndefined(user.subjects.get(id));
                }
            };
        },

        ui : {
            'submit'   : '.js-save',
            'subject'  : '.js-subject',
            'subjects' : '.js-subjects',
            'tabs'     : '.js-tab'
        },

        collectionEvents : {
            'sync' : 'render'
        },

        fields : [
            [
                'subjects',
                function () {
                    var $els = $(this.ui.subject.selector + ':checked');

                    return _.map($els, function (el) {
                        return new this.collection.model({
                            'id' : el.value
                        });
                    }, this);
                }
            ]
        ],

        onRender : function () {
            if (this.ui.tabs.length > 0) {
                this.ui.tabs.first().click();
                this.ui.submit.removeAttr('disabled');
            }
        }

    }));

});
