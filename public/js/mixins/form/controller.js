define([
    'base'
], function (
    Base
) {

    return {

        errorCodes : {
            'ERR_UNEXPECTED'            : "An unexpected error has occured. Please try again later.",
            'ERR_UNAUTHORIZED'          : "You're not authorized to do that.",
            'ERR_INVALID'               : "Something doesn't look right.",
            'ERR_INVALID_QUIZ_ANSWER'   : "Not quite… try again!",
            'ERR_INCORRECT_PASSWORD'    : "That password doesn't look right.",
            'ERR_INCORRECT_CREDENTIALS' : "We couldn't find an account for that email. <a href='" + laroute.route('register.student') + "'>Create one now?</a>",
            'ERR_GEOCODE_NO_RESULT'     : "That address doesn't look right. Please ensure it's correct and try again.",
            'ERR_TOKEN_MISMATCH'        : "Your session has timed out. Please refresh the page and try again.",
            'ERR_IMAGE_UNREADABLE'      : "We couldn't open the image you've just uploaded. Please try again ensuring the image you're uploading is either a jpg or a png.",
            'ERR_IMAGE_WRONGFORMAT'     : "The image you've just attempted to upload is not the correct format, please try again.",
            'ERR_IMAGE_NOPDF'           : "Sorry, we cannot accept Identity Documents in PDF format, please upload a photographic image (JPEG, JPG or PNG)"
        },


        onFormSubmit : function (attrs) {
            this.view.hideErrors();

            this.view.disableSubmit();

            _.progress().start();

            this.save(attrs);
        },

        onSyncErrorOrInvalid : function () {
            _.progress().done();
        },

        onSyncError : function (model, response) {
            if (this.view) {
                this.view.enableSubmit();
            }

            var json = response.responseJSON;

            this.parseErrorJson(json, response.status);
        },

        parseErrorJson : function (json, status) {
            if (json && status >= 300) {
                var errors =  {};

                _.each(json.errors, function (error) {
                    errors[error.field] = error.detail;
                });

                this.showErrors(json.message, errors);
            } else {
                this.showErrors('ERR_UNEXPECTED');
            }
        },

        onInvalid : function (model, errors) {
            this.view.enableSubmit();

            this.showErrors('ERR_INVALID', errors);
        },

        showErrors : function (message, errors) {
            if (_.has(this.errorCodes, message)) {
                message = this.errorCodes[message];
            }

            _.toast(message, 'error');

            if (this.view && errors) {
                this.view.showErrors(errors);
            }
        }

    };

});
