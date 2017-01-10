define([
    'dropzone',
    'base',
    'requirejs-text!apps/tutor_account/admin_billing/templates/layout.html'
], function (
    Dropzone,
    Base,
    template
) {

    return _.patch(Base.LayoutView.extend({

        mixins : ['FormLayout', 'FieldsLayout'],

        template : _.template(template),

        templateHelpers : function () {
            var status = this.model.identity_document.get('status');

            return {
                showIntro    : _.indexOf(_.config('cookies'), 'dismissable_tutor_account_identification_introduction') >= 0,
                showVerified : _.indexOf(_.config('cookies'), 'dismissable_tutor_account_identification_verified')     >= 0,
                lockFields   : status && status !== 'unverified'
            }
        },

        modelEvents : {
            'sync' : 'render'
        },

        ui : {
            'legal_first_name' : '.js-legal-first-name',
            'legal_last_name'  : '.js-legal-last-name',
            'dob.day'          : '.js-dob-day',
            'dob.month'        : '.js-dob-month',
            'dob.year'         : '.js-dob-year',
            'document'         : '.js-document',
            'document_preview' : '.js-document-preview'
        },

        fields : [
            'legal_first_name',
            'legal_last_name',
            'dob.day',
            'dob.month',
            'dob.year',
            'document'
        ],

        initialize : function () {
            this.listenTo(this.model.identity_document, 'change', this.render);
        },

        showDocumentPreview : function (file, dataUrl) {
            this.ui.document_preview.html('<img src="' + dataUrl + '">');
        }

    }));

});
