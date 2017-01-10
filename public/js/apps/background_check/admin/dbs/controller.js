define([
    'base',
    'events',
    'entities/user',
    'apps/background_check/admin/dbs/views/layout'
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
                'user'  : this.user,
                'model' : this.model
            });

            this.listenTo(this.view, 'show', this.onShow);
            this.listenTo(this.view, 'form:submit', this.onFormSubmit);

            this.listenTo(this.view, 'dropzone-sync', this.onDropzoneSync);
            this.listenTo(this.view, 'dropzone-error', this.onDropzoneError);

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

                if (this.view.dropzone.files.length > 0) {
                    this.view.dropzone.processQueue();
                } else {
                    this.saveBackgroundCheck();
                }
            }
        },

        onSyncSuccess : function (model, json) {
            _.toast('Saved!', 'success');
            this.view.render();
        },

        onSyncError : function () {
        },

        onDropzoneSync : function (file, response) {
            _.progress().inc(0.25);

            this.attributes.image_upload = response.data;

            this.saveBackgroundCheck();
        },

        onDropzoneError : function (file, json, response) {
            this.onSyncErrorOrInvalid();

            if ( ! response) {
                if ( ! file.accepted) {
                    var message = 'ERR_IMAGE_WRONGFORMAT';
                    if (file.type == 'application/pdf') {
                        message = 'ERR_IMAGE_NOPDF'
                    }
                    var response = {
                        'status': 400,
                        'responseJSON': {
                            'message': message
                        }
                    }
                }
            }

            this.onSyncError(null, response);
        },

        saveBackgroundCheck : function () {
            this.model.save(this.attributes);
        },

        validate : function (attributes) {
            var errors = [];

            var isRejected      = this.model.isRejectedStatus(attributes.admin_status);
            var isCustomReject  = isRejected && this.model.isCustomRejectReason(attributes.rejected_for);

            if (!isRejected && _.isEmpty(attributes.issued_at)) {
                errors.push({
                    'field' : 'issued_at',
                    'detail' : 'The issued at field is required'
                });
            }

            if (isRejected && _.isEmpty(attributes.rejected_for)) {
                errors.push({
                    'field' : 'rejected_for',
                    'detail' : 'Please select the reason'
                });
            }

            if (isCustomReject && _.isEmpty(attributes.reject_comment)) {
                errors.push({
                    'field'  : 'reject_comment',
                    'detail' : 'Please enter the reason'
                });
            }

            if (_.isEmpty(attributes.admin_status)) {
                errors.push({
                    'field' : 'admin_status',
                    'detail' : 'The admin status field is required'
                });
            }

            if (this.view.dropzone.files.length === 0 && ! this.view.model.image.get('uuid')) {
                errors.push({
                    'field'  : 'image_upload',
                    'detail' : 'An image is required.'
                });
            }

            return errors;
        }

    }));

});
