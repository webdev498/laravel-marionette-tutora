define([
    'base',
    'events',
    'entities/user',
    'apps/background_check/dbs/views/layout'
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
            Event.trigger('requirement:pending', {
                'section' : 'profile',
                'name'    : 'background_check'
            });

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
            this.trigger('background-saved');
        },

        onSyncError : function () {
            Event.trigger('requirement:incompleted', {
                'section' : 'profile',
                'name'    : 'background_check'
            });
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
            this.attributes.uuid = null;
            this.model.save(this.attributes);
        },

        validate : function (attributes) {
            var errors = [];

            if (this.view.dropzone.files.length === 0) {
                errors.push({
                    'field'  : 'image_upload',
                    'detail' : 'An image is required.'
                });
            }

            return errors;
        }

    }));

});
