define([
    'base',
    'events',
    'entities/user',
    'apps/background_check/dbs_update/views/layout'
], function (
    Base,
    Event,
    User,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController'],

        initialize : function (options) {
            this.region = options.region;
            this.user   = options.user;
            this.model  = options.model;

            this.view     = new LayoutView({
                'model' : this.model
            });

            this.listenTo(this.view, 'show', this.onShow);
            this.listenTo(this.view, 'form:submit', this.onFormSubmit);

            this.listenTo(this.model, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.model, 'sync', this.onSyncSuccess);
            this.listenTo(this.model, 'error', this.onSyncError);
            this.listenTo(this.model, 'invalid', this.onInvalid);

            this.region.show(this.view);
        },

        save : function () {
            this.view.hideErrors();

            var attributes = object_undot(this.view.getFieldsData());
            var errors     = this.validate(attributes);

            if (errors.length > 0) {
                this.onSyncErrorOrInvalid();
                this.onSyncError(null, {
                    'status'       : 400,
                    'responseJSON' : {
                        'message' : 'ERR_INVALID',
                        'errors'  : errors
                    }
                });
            } else {
                this.attributes = object_undot(attributes);

                this.saveBackgroundCheck();
            }
        },

        onSyncSuccess : function (model, json) {
            _.toast('Saved!', 'success');
            this.trigger('background-saved');
        },

        onSyncError : function () {
        },

        saveBackgroundCheck : function () {
            this.attributes.uuid = null;
            this.model.save(this.attributes);
        },

        validate : function (attributes) {
            var errors = [];

            if (_.isEmpty(attributes.certificate_number)) {
                errors.push({
                    'field' : 'certificate_number',
                    'detail' : 'The certificate number field is required'
                });
            }

            if (_.isEmpty(attributes.last_name)) {
                errors.push({
                    'field' : 'last_name',
                    'detail' : 'The last name field is required'
                });
            }

            if (_.isEmpty(attributes.dob)) {
                errors.push({
                    'field' : 'dob',
                    'detail' : 'The date of birth field is required'
                });
            }

            return errors;
        }

    }));

});
