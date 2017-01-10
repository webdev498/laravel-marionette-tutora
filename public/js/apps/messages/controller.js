define([
    'base',
    'entities/user',
    'apps/messages/create/controller',
    'apps/messages/admin_index/controller'
], function (
    Base,
    User,
    CreateController,
    AdminIndexController
) {

    return Base.Controller.extend({

        create : function (options) {
            options = _.isObject(options) ? options : {};
            console.log('messages created');
            return new CreateController(_.extend({
                'app'    : this.app,
                'region' : this.app.dialogueRegion
            }, options));
        },

        openMessageForm : function() {
            $('#intent').val(1);
            $('#reason').val('');
            $('textarea[name="body"]').val('');
            $('form.message-form').addClass('message-form--active');
            $('.tutor-intent').removeClass('tutor-intent--active');
            $('.can-help').show();
            $('.cannot-help').hide();
            $('.tar .help-yes').hide();
            $('.tar .help-no').show();
            $('.tar .change-reason').hide();
            $('.popover').removeClass('popover--open');
        },

        openReasonDialogue : function() {

            // Open dialogue/model box
            var tpl = $('#reason-dialogue');
            $(tpl).addClass('dialogue--open');

            $('#reason').val('');
            $("#reason-dialogue input:radio").removeAttr("checked");
            $("#reason-dialogue .radios__item--checked").removeClass("radios__item--checked");
            $("#reason-dialogue button").addClass('btn--disabled').attr("disabled");

            $('.radios__item').on('click',
                function(){
                    $('.btn-reason').removeAttr('disabled').removeClass('btn--disabled');
                }
            );

            $('textarea[name="body"]').on('keyup', function() {
                $('.popover').removeClass('popover--open');
            });
        },

        closeReasonDialogue : function () {
            $("#reason-dialogue input:radio").removeAttr("checked");
            $("#reason-dialogue .btn-reason").addClass('btn--disabled').attr("disabled");
            $('#intent').val(1);
            $('#reason').val('');
            $('.dialogue').removeClass('dialogue--open');
            $('.tar .change-reason').hide();
            $('.tar .help-yes').hide();
        },

        setReason : function (selected) {
            var reason_msg = $(selected).data('message');
            if (reason_msg) {
                $('.popover').addClass('popover--open');
            } else {
                $('.popover').removeClass('popover--open');
            }

            $('.popover').on('click', function() {
                $('.popover').removeClass('popover--open');
            });

            $('textarea[name="body"]').val(reason_msg);
            $('#reason').val( $(selected).val() );
            $('#intent').val(0);
            $('.dialogue').removeClass('dialogue--open');
            $('form.message-form').addClass('message-form--active');
            $('.tutor-intent').removeClass('tutor-intent--active');
            $('.can-help').show();
            $('.cannot-help').hide();
            $('.tar .help-no').hide();
            $('.tar .change-reason').show();
            $('.tar .help-yes').show();
        },

        show : function () {
            var $messages = $('.messages');
            console.log('in show');
            if ($messages.length > 0) {
                var $window   = $(window);
                var $list     = $('.messages__list');
                var minus     = _.reduce([
                    $('.page-head'),
                    $('.message-head'),
                    $('.message-form')
                ], function (memo, $el) {
                    return memo + $el.outerHeight();
                }, 48); // 2 x $spacing-unit

                var resize = function () {
                    if ($(document.activeElement).is('input') || $(document.activeElement).is('textarea')) {
                        return;
                    }

                    var height = $window.height() - minus;
                    if (height < 190) {
                        height = 190;
                    }

                    $messages.css('height', height);
                    $messages.scrollTop($list.prop('scrollHeight'));
                };

                $window.on('resize', resize);

                resize();
            }

            var self = this;
            $('.help-yes').on('click', function (e) {
                console.log('clicked yes');
                e.preventDefault();
                self.openMessageForm();
            });

            $('.help-no').on('click', function (e) {
                console.log('clicked no');
                e.preventDefault();
                self.openReasonDialogue();
            });

            $('#reason-dialogue .btn-reason').on('click', function(e) {
                e.preventDefault();
                var selected = $('input[name="reason_select"]:checked');
                self.setReason(selected);
            });

            $('.js-close').on('click', function (e) {
                self.closeReasonDialogue();
            });

            $('#dialogue .go-offline').on('click', function(e) {
                e.preventDefault();
                user = User.current();
                user.save({
                    'profile' : {
                        'status' : 'offline'
                    }
                }, {
                    'patch' : true
                });

                $('.dialogue').removeClass('dialogue--open');
                _.toast('You are now offline', 'error');
            });
        },

        adminIndex : function (options) {
            options = _.isObject(options) ? options : {};

            return new AdminIndexController(_.extend({
                'app'    : this.app
            }, options));
        }
    });

});