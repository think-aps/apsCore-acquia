/**
 * @file
 * Internal page activity tracking.
 */

(function ($, Drupal, drupalSettings) {
	let apsDiscovr = analytics;

	// Window activities to send tracking data
	$(window).on('focus blur beforeunload unload', function ($e) {
		apsDiscovr.track('Window', {
			"type": $e.type,
			"userId": drupalSettings.user.uid,
		});
	});

	$(document).ready(function () {
		console.log('Tracking is active');

		// Tracks the user clicking links
		$("[href]").on('click', function (event) {
			apsDiscovr.track('Clicked Link', {
				"link": $(this)[0].href,
				"userId": drupalSettings.user.uid,
			})
		});

		// Track the user changing tabs
		$("ul.quicktabs-tabs li a, ul.navtabs-tabs li a").once().bind('click', function () {
			apsDiscovr.track('Tab', {
				"name": $(this).text(),
				"userId": drupalSettings.user.uid,
			});
		});

		/*---------------------------- LIVE ACTIVITY ----------------------------*/

		// Interval function for check if users are present every 30 seconds
		if (!drupalSettings.apsDiscovr.userSettings.ignoreTracking) {
			setInterval(function () {
				apsDiscovr.track('User present', {
					"userId": drupalSettings.user.uid,
				})
			}, 45000);
		}

		/*--------------------------- VIDEO ANALYTICS ---------------------------*/

		// Setup analytics for individual iframe instance
		function vimeoDiscovrInit(iframe) {
			let player = new Vimeo.Player(iframe);
			let videoDiscovr = new analytics.plugins.VimeoAnalytics(player, '0f780f9911336b6a8409191e7e2c46ac');

			videoDiscovr.initialize();
		}

		// Loop through each visible Vimeo iframe, and apply analytics
		setTimeout(function () {
			let iframe_arr = $('iframe[src*="vimeo.com"]:visible');

			for (let i = 0; i < iframe_arr.length; i++) {
				vimeoDiscovrInit(iframe_arr.eq(i));
			}
		}, 1000);

		// Reference the newly loaded Magnific Popup iframe
		if (typeof $.magnificPopup !== "undefined") {
			$.magnificPopup.instance.open = function (data) {
				$.magnificPopup.proto.open.call(this, data);
				if ($('.mfp-content iframe[src*="vimeo.com"]').length) {
					setTimeout(function () {
						let popup_arr = $('.mfp-content iframe[src*="vimeo.com"]');

						for (let i = 0; i < popup_arr.length; i++) {
							vimeoDiscovrInit(popup_arr.eq(i));
						}
					}, 1000);
				}
			};
		}
	});
})(jQuery, Drupal, drupalSettings);
