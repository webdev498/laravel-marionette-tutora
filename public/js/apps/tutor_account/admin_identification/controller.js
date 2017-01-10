define([
    'base',
    'dropzone',
    'events',
    'entities/user',
    'apps/tutor_account/admin_identification/views/layout'
], function (
    Base,
    Dropzone,
    Event,
    User,
    LayoutView
) {

    return _.patch(Base.Controller.extend({

        mixins : ['FormController'],

        initialize : function (options) {
            this.user     = options.user;

            this.showLayout();
        },

        showLayout: function() {
            _.progress().done();
            this.stopListening(this.user);

            this.view = new LayoutView({
                'model' : this.user
            });

            this.listenTo(this.view, 'render', this.onRender);
            this.listenTo(this.view, 'show', this.onShow);
            this.listenTo(this.view, 'form:submit', this.onFormSubmit);
            this.listenTo(this.view, 'destroy', this.destroy);

            this.listenTo(this.user, 'sync error invalid', this.onSyncErrorOrInvalid);
            this.listenTo(this.user, 'sync', this.onSyncSuccess);
            this.listenTo(this.user, 'error', this.onSyncError);
            this.listenTo(this.user, 'invalid', this.onInvalid);

            this.listenTo(Event, 'requirement:completed', function (requirement, data) {
                if (requirement.name == 'identification') {
                    this.user.identity_document.set(data.identity_document);
                    this.region.currentView.render();
                }
            }, this);

            this.listenTo(Event, 'requirement:incompleted', function (requirement, data) {
                if (requirement.name == 'identification') {
                    this.user.identity_document.set(data.identity_document);
                    this.region.currentView.render();
                }
            }, this);

            this.region.show(this.view);
        },

        onRender : function () {
            var status = this.user.identity_document.get('status');

            this.dropzone = new Dropzone(this.view.ui.document[0], {
                'url' : laroute.route('api.users.identity-document.create', {
                    'uuid' : this.user.id
                }),
                'headers' : {
                    'X-CSRF-Token' : _.config('csrf_token')
                },
                // Thumbnail keeps aspect ratio, 150px high though.
                'thumbnailWidth'   : null,
                'thumbnailHeight'  : 150,
                // Accepted file types
                'acceptedFiles'    : ".jpeg,.jpg,.png",
                // Don't upload instantly and only allow 1 file
                'autoProcessQueue' : false,
                'maxFiles'         : 1,
                // Els
                'paramName'        : 'file',
                'clickable'        : [this.view.ui.document[0], this.view.ui.document_preview[0]],
                // Callbacks
                'addedfile'        : _.bind(this.onDropzoneAddedFile, this),
                'thumbnail'        : _.bind(this.view.showDocumentPreview, this.view),
                'success'          : _.bind(this.onDropzoneSync, this),
                'error'            : _.bind(this.onDropzoneError, this)
            });
            
        },

        save : function (attributes) {
            this.view.hideErrors();

            var attributes = object_undot(attributes);
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

                if (this.dropzone.files.length > 0) {
                    this.dropzone.processQueue();
                } else {
                    this.attributes.identity_document = this.user.identity_document.toJSON();
                    this.saveUser();
                }
            }
        },

        onDropzoneAddedFile : function (file) {
            // Only allow 1 file
            if (this.dropzone.files.length > 1) {
                this.dropzone.removeFile(this.dropzone.files[0]);
            }
        },

        onDropzoneSync : function (file, response) {
            _.progress().inc(0.25);

            this.attributes.identity_document = response;

            this.saveUser();
        },

        saveUser : function () {
            this.user.save(this.attributes, {
                'patch' : true
            });
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

        onSyncSuccess : function (user, json) {
            // Requirements overlap
            if (user.profile.get('status') === 'new') {
                _.toast('Uploaded &amp; saved!', 'success');

                _.delay(function () {
                    Event.trigger('requirement:pending', {
                        'section' : 'account',
                        'name'    : 'identification'
                    });

                    _.toast('Checking identification...', 'warning');
                }, 1000);
            }

            this.view.enableSubmit();
        },


        validate : function (attributes) {
            var errors = [];

            if (this.dropzone.files.length === 0 && ! this.user.identity_document.get('uuid')) {
                errors.push({
                    'field'  : 'document',
                    'detail' : 'An identification document is required.'
                });
            }

            if (_.isEmpty(attributes.legal_first_name)) {
                errors.push({
                    'field' : 'legal_first_name',
                    'detail' : 'The first name field is required'
                });
            }

            if (_.isEmpty(attributes.legal_last_name)) {
                errors.push({
                    'field' : 'legal_last_name',
                    'detail' : 'The last name field is required'
                });
            }

            if (_.isEmpty(attributes.dob.day)) {
                errors.push({
                    'field' : 'dob.day',
                    'detail' : 'The day field is required'
                });
            }

            if (_.isEmpty(attributes.dob.month)) {
                errors.push({
                    'field' : 'dob.month',
                    'detail' : 'The month field is required'
                });
            }

            if (_.isEmpty(attributes.dob.year)) {
                errors.push({
                    'field' : 'dob.year',
                    'detail' : 'The year field is required'
                });
            }

            return errors;
        }

    }));

});
