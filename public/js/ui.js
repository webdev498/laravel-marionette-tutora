define([
    'backbone.marionette',
    'nprogress'
], function (
    Marionette,
    Nprogress
) {

    var $window   = $(window);
    var $document = $(document);
    var $html     = $('html');

    Nprogress.configure({
        showSpinner : false
    });

    // Resize the page footer
    (function () {
        var $page   = $('.page');
        var $footer = $('.page__footer');

        var resize = function () {
            $page.css('marginBottom', $footer.outerHeight());
        };

        $window.on('resize', resize);

        resize();
    }).call(this);

    // Burger menu
    (function () {
        var $site_nav = $('.site-nav');

        $site_nav.on('click', '.site-nav__burger', function () {
            $site_nav.addClass('site-nav--open');
        });

        $site_nav.on('click', '.site-nav__cross, .site-nav__link', function () {
            $site_nav.removeClass('site-nav--open');
        });
    }).call(this);

    // Page drawer
    (function () {
        var $drawer   = $('.page-drawer');
        var openClass = 'page-drawer--open';

        $document.on('click', 'a.js-how-it-works', function (e) {
            if (e.preventDefault) {
                e.preventDefault();
            } 

            if ($drawer.hasClass(openClass)) {
                $drawer.removeClass(openClass);
            } else {
                $('html, body').animate({
                    'scrollTop' : 0
                }, 500);
                $drawer.addClass(openClass);
            }

            return false;
        });
    }).call(this);

    // Radio
    (function () {
        $(document).on('click', '.radios__item', function (e) {
            var $item   = $(e.currentTarget);
            var $parent = $item.parents('.radios');
            var $input  = $item.find('.radios__input');

            if ( ! $input.is(':checked')) {
                $parent.find('.radios__item').removeClass('radios__item--checked');
                $parent.find('.radios__input').removeAttr('checked');
                $parent.find('.radios__input').prop('checked', false);

                $item.addClass('radios__item--checked');
                $input.attr('checked', true);
                $input.prop('checked', true);
            }
        });
    }).call(this);

    // Radio (ratings)
    (function () {
        $(document).on('click', '.field__rating__item', function (e) {
            var $item  = $(e.currentTarget);
            var $field = $item.parents('.field');
            var $input = $item.find('.field__rating__input');

            $field.find('.field__rating__item').removeClass('field__rating__item--checked');
            $field.find('.field__rating__input').removeAttr('checked');
            $field.find('.field__rating__input').prop('checked', false);

            $item.addClass('field__rating__item--checked');
            $input.attr('checked', true);
            $input.prop('checked', true);
        });
    }).call(this);

    // Checkbox
    (function () {
        $(document).on('click', '.checkbox__box, .checkbox__label', function (e) {
            var $item   = $(e.currentTarget);
            var $parent = $item.parents('.checkbox');
            var $input  = $parent.find('.checkbox__input');

            if ($input.is(':checked')) {
                $parent.removeClass('checkbox--checked');
                $input.removeAttr('checked');
                $input.prop('checked', false);
                $input.trigger('changed');
            } else {
                $parent.addClass('checkbox--checked');
                $input.attr('checked', true);
                $input.prop('checked', true);
                $input.trigger('changed');
            }
        });
    }).call(this);

    // Selects
    (function () {
        $(document).on('change', '.select .select__field', function (e) {
            var $field  = $(e.currentTarget);
            var $parent = $field.parents('.select');
            var $value  = $parent.find('.select__value');

            var klass = 'select--show';

            if ($field.val() === '') {
                $parent.removeClass(klass);
                $value.html('');
            } else {
                $parent.addClass(klass);
                $value.html($field.find(':selected').text());
            }
        });
    }).call(this);

    // Tabs
    (function () {
        $(document).on('click', '.tabs__link', function (e) {
            var $link = $(e.currentTarget);

            var group = $link.data('tab-group');
            var name  = $link.data('tab-name');

            if (group && name) {
                if (e.preventDefault) {
                    e.preventDefault();
                }

                var $links = $('.tabs__link[data-tab-group="' + group + '"]');

                $links.removeClass('tabs__link--active');
                $link.addClass('tabs__link--active');

                var $contents = $('.tab-content[data-tab-group="' + group + '"]');
                var $content  = $('.tab-content[data-tab-group="' + group + '"][data-tab-name="' + name + '"]');

                $contents.removeClass('tab-content--active');
                $content.addClass('tab-content--active');

                var href = $link.attr('href');

                if (href !== '#') {
                    Backbone.history.navigate(href);
                }

                return false;
            }
        });
    }).call(this);

    // Dismissable
    (function () {
        $(document).on('click', '.js-dismissable .js-close', function (e) {
            var $close       = $(e.currentTarget);
            var $dismissable = $close.parents('.js-dismissable');

            $dismissable.css('display', 'none');

            $.post('/api/cookies', {
                'key'   : 'dismissable_' + $dismissable.data('dismissable-id'),
                'value' : true
            });
        });
    }).call(this);
});
