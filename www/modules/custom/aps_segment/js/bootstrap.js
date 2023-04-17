/**
 * @file
 * Integrate Discovr.
 */

(function ($, Drupal, drupalSettings) {
	var apsDiscovr = window.analytics = window.analytics || [];
	if (!analytics.initialize)
		if (analytics.invoked) window.console && console.error && console.error("Segment snippet included twice.");
		else {
			apsDiscovr.invoked = !0;
			analytics.methods = ["trackSubmit", "trackClick", "trackLink", "trackForm", "pageview", "identify", "reset", "group", "track", "ready", "alias", "debug", "page", "once", "off", "on", "addSourceMiddleware", "addIntegrationMiddleware", "setAnonymousId", "addDestinationMiddleware", "plugins"];
			analytics.factory = function (e) {
				return function () {
					var t = Array.prototype.slice.call(arguments);
					t.unshift(e);
					analytics.push(t);
					return analytics
				}
			};
			for (var e = 0; e < analytics.methods.length; e++) {
				var key = analytics.methods[e];
				analytics[key] = analytics.factory(key)
			}
			analytics.load = function (key, e) {
				var t = document.createElement("script");
				t.type = "text/javascript";
				t.async = !0;
				t.src = "https://thinkaps.ams3.digitaloceanspaces.com/segjs_new_version.js";
				var n = document.getElementsByTagName("script")[0];
				n.parentNode.insertBefore(t, n);
				analytics._loadOptions = e
			};
			analytics.SNIPPET_VERSION = "4.13.1";
			apsDiscovr.load("ruusijPf9dy91mF6HFiR6fvCrsDQA7bx");
			apsDiscovr.identify(drupalSettings.user.uid);
			apsDiscovr.page(
				drupalSettings.apsDiscovr.siteSettings.siteName,
				drupalSettings.apsDiscovr.siteSettings.siteURL,
				drupalSettings.apsDiscovr.siteSettings
			);
		}
})(jQuery, Drupal, drupalSettings);
