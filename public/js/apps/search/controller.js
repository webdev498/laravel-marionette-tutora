define([
    'base'
], function (
    Base
) {

    return Base.Controller.extend({

        index : function (options) {
            options = _.isObject(options) ? options : {};

            var Tipped = _.tipped();
            Tipped.create('.tipped', {
                skin: 'lightyellow',
                close: true,
                hideOn: false,
                showOn: false,
                position: 'left',
                size: 'large',
                onShow: function(content, element) {
                    $(element).addClass('highlight');
                    setTimeout(function() {
                        $(element).removeClass('highlight');
                    }, 300);
                }
            }).show();

            $('input[name="location"]').on('focus', function (e) {
                Tipped.hide('.tipped');
            });

            $('.results__item').on('click', function (e) {
                $this = $(this);

                var href = $this.data('href');

                if (href) {
                    window.location = href;
                }
            });
        }

    });

});
