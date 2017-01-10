define([
	'underscore'
], function (_) {
	if (!_.isUndefined(window.ZiggeoApi)) {
		ZiggeoApi.token = '35d1cf5177876d75375151131115a57b';
		ZiggeoApi.Config.webrtc = true;

		return {

			embedDefault: function (selector, modes, specifics) {
				ZiggeoApi.Embed.embed(selector, _.extend({
					width: '320',
					height: '240'
				}, specifics));
			},

			embedCustomRatio: function (selector, width, ratio, specifics) {
				ZiggeoApi.Embed.embed(selector, _.extend({
					width: width,
					height: width / ratio
				}, specifics));
			},

			embedResponsive: function (selector, $wrapper, ratio, specifics) {
				var initialWidth = $wrapper.width();

				this.embedCustomRatio(selector, initialWidth, ratio, specifics);

                ZiggeoApi.Events.on("ready_to_play", function (data) {
                    // set of predefined selectors to be affected
                    var selectors = ['.video-player-outer', '[data-view-id="cid_7"]', selector, '.video-player-content'];

                    // wait while the "style" attribute appears
                    var timer = setInterval(function () {
                        selectors = selectors.filter(function (element) {
                            if ($(element).is('[style]')) {
                                // and clear it
                                $(element).attr('style', '');
                                return false;
                            } else {
                                return true;
                            }
                        });

                        if (selectors.length == 0) {
                            // remove width and height attributes from the video element
                            $('.video-player-content').removeAttr('width').removeAttr('height');
                            clearInterval(timer);
                        }
                    }, 1000);
                });

				/*$(window).resize(function () {
					if ($wrapper.width() !== initialWidth) {
						initialWidth = $wrapper.width();
						$(selector).removeAttr('style')
							.empty();
						self.embedCustomRatio(selector, initialWidth, ratio, specifics);
					}
				})*/
			},

			Embed: ZiggeoApi.Embed,
			
			Events: ZiggeoApi.Events,

			Videos: ZiggeoApi.Videos,

			Streams: ZiggeoApi.Streams

		}
	}
});