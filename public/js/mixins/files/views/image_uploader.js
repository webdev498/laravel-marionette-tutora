define([
    'base',
    'dropzone'
], function (
    Base,
    Dropzone
) {

    return {

        onRender : function () {
            this.dropzone = new Dropzone(this.ui.image_upload[0], {
                'url' : laroute.route('api.files.images.create'),
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
                'clickable'        : [this.ui.image_upload[0], this.ui.image_upload_preview[0]],
                // Callbacks
                'addedfile'        : _.bind(this.onDropzoneAddedFile, this),
                'thumbnail'        : _.bind(this.showDocumentPreview, this),
                'success'          : _.bind(this.onDropzoneSync, this),
                'error'            : _.bind(this.onDropzoneError, this)
            });
        },

        showDocumentPreview : function (file, dataUrl) {
            this.ui.image_upload_preview.html('<img src="' + dataUrl + '">');
        },

        onDropzoneAddedFile : function (file) {
            // Only allow 1 file
            if (this.dropzone.files.length > 1) {
                this.dropzone.removeFile(this.dropzone.files[0]);
            }
        },

        onDropzoneSync : function (file, response) {
            _.toast('Image uploaded!', 'success');
            this.trigger('dropzone-sync', file, response);
        },

        onDropzoneError : function (file, json, response) {
            this.trigger('dropzone-error', file, json, response);
        },

        ui : {
            'image_upload'         : '.js-image-upload',
            'image_upload_preview' : '.js-image-upload-preview'
        },

        fields : [
            'image_upload'
        ]

    };

});