import * as cookieConsent from 'klaro';
import { map } from 'lodash';

window.addEventListener( 'load', () => {
	const exportedOptions = window.cookieConsentConfig || {};

	exportedOptions.apps = map(exportedOptions.apps, function(app) {
		// callback function: turn the function string from the database into a function
		if ( 'string' === typeof app.callback ) {
			app.callback = new Function('consent, app', app.callback);
		}

		return app;
	});

	cookieConsent.renderKlaro(exportedOptions);
	window.cookieConsentConfig = exportedOptions;
	window.cookieConsent = cookieConsent;
} );
