define([
    'base',
    'events',
    'dropzone',
    'entities/user'
], function (
    Base,
    Event,
    Dropzone,
    User
) {

    return Base.ItemView.extend({

        template : false,

        ui : {
            'img'       : '.js-img',
            'preview'   : '.js-preview',
            'clickable' : '.js-click'
        },

        initialize : function () {
            this.bindUIElements();

            this.user = User.current();

            this.$el.dropzone({
                'url' : '/api/users/' + this.user.id + '/profile-picture',
                'headers' : {
                    'X-CSRF-Token' : _.config('csrf_token')
                },
                'maxFilesize'    : 4, // mb
                'acceptedFiles'  : 'image/jpeg,image/png,image/bmp,image/gif,image/svg',
                'paramName'      : 'picture',
                'clickable'      : this.ui.clickable.selector,
                'addedfile'      : _.bind(this.onAddedFile, this),
                'thumbnail'      : _.bind(this.onThumbnail, this),
                'uploadprogress' : _.bind(this.onUploadProgress, this),
                'complete'       : _.bind(this.onComplete, this),
                'success'        : _.bind(this.onSync, this),
                'error'          : _.bind(this.onError, this)
            });

            this.listenTo(Event,'tutor.profile.picture:edit', function () {
                this.ui.clickable.click();
            });
        },

        onAddedFile : function (file) {
            _.progress().start();

            Event.trigger('requirement:pending', {
                'section' : 'profile',
                'name'    : 'profile_picture'
            });
        },

        onThumbnail : function (file, dataUrl) {
            if (file.status !== "error") {
                this.ui.preview.html('<img src="' + dataUrl + '">');
            }
        },

        onUploadProgress : function (file, progress, bytesSent) {
            // 
        },

        onComplete : function () {
            _.progress().done();
        },

        onSync : function (file, response) {
            if (_.has(response, 'data') && _.has(response.data, 'large')) {
                var image = new Image();

                image.onload = _.bind(function () {
                    this.ui.preview.find('img').remove();
                    this.ui.img.find('img').attr('src', image.src);  
                }, this);

                image.src = response.data.large + '?cache-breaker=' + new Date().getTime();
            }

            _.toast('Uploaded and saved!', 'success');
        },

        onError : function (file, error, response) {

            Event.trigger('requirement:incompleted', {
                'section' : 'profile',
                'name'    : 'profile_picture'
            });

            if (response) {
                json = JSON.parse(response.responseText);

                switch (response.status) {
                    case 413:
                    case "413":
                        error = 'File is too big. Max filesize: 2MiB.';
                        break;
                    case 400:
                    case "400":
                        error = json.errors[0].detail;
                        break;
                    default:
                        error = 'An unexpected error has occured. Please try again later.';
                }
            }

            this.ui.preview.find('img').remove();

            _.toast(error, 'error');
        }

    });

});
