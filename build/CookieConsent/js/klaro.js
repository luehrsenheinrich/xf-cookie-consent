import * as cookieConsent from 'klaro';
import { map, filter } from 'lodash';

window.addEventListener( 'load', () => {
	const exportedOptions = window.cookieConsentConfig || {};

	exportedOptions.apps = map(exportedOptions.apps, function(app) {
		// callback function: turn the function string from the database into a function
		if ( 'string' === typeof app.callback ) {
			app.callback = new Function('consent, app', app.callback);
		}

		// Turn the cookie strings into regular expressions
		if( 'object' === typeof app.cookies ) {
			app.cookies = map( app.cookies, function( cookie ) {
				if(cookie.length === 0 || !cookie.trim()) {
					return null;
				}
				return new RegExp( cookie );
			}).filter(function (el) {
			  return el != null;
			});
		}

		return app;
	});

	cookieConsent.renderKlaro(exportedOptions);
	window.cookieConsentConfig = exportedOptions;
	window.cookieConsent = cookieConsent;

	document.addEventListener('click', ( e ) => {
		if(!e.target.matches('.js-cookie-consent-modal')) {
			return;
		}

		cookieConsent.show(exportedOptions);
	});
} );
