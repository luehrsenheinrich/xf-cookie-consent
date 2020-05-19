<?php

namespace LH\CookieConsent;

class CookieConsent {
	public static function getCookieConsentConfigObject()
	{
		$appFinder = \XF::finder('LH\CookieConsent:App');
		$apps = $appFinder->where('active', 1)->order('displayOrder', 'ASC')->fetch();

		$purposeFinder = \XF::finder('LH\CookieConsent:Purpose');
		$purposes = $purposeFinder->fetch();

		$apps_array = array();
		$translations = array(
			'consentModal' => array(
				// Information that we collect
				'title' => \XF::phrase('cc_information_we_collect'),
				// Here you can see and customize the information that we collect about you.
				'description' => \XF::phrase('cc_see_and_customize_information'),
				'privacyPolicy' => array(
					// privacy policy
					'name' => \XF::phrase('cc_privacy_policy'),
					// To learn more, please read our {privacyPolicy}.
					'text' => \XF::phrase('cc_please_read_privacy_policy'),
				)
			),
			'consentNotice' => array(
				// There were changes since your last visit, please update your consent
				'changeDescription' => \XF::phrase('cc_there_were_changes'),
				// We collect and process your personal information for the following purposes: {purposes}.
				'description' => \XF::phrase('cc_we_collect_for_purposes'),
				// Customize
				'learnMore' => \XF::phrase('cc_customize'),
			),
			// Accept
			'ok' => \XF::phrase('cc_accept'),
			// Save
			'save' => \XF::phrase('cc_save'),
			// Decline
			'decline' => \XF::phrase('cc_decline'),
			// Close
			'close' => \XF::phrase('cc_close'),
			// Accept All
			'acceptAll' => \XF::phrase('cc_accept_all'),
			// Accept Selected
			'acceptSelected' => \XF::phrase('cc_accept_selected'),
			'app' => array(
				'disableAll' => array(
					// Toggle all apps
					'title' => \XF::phrase('cc_toggle_all'),
					// Use this switch to enable/disable all apps.
					'description' => \XF::phrase('cc_toggle_all_explain'),
				),
				'optOut' => array(
					// (opt-out)
					'title' => \XF::phrase('cc_opt_out'),
					// This app is loaded by default (but you can opt out)
					'description' => \XF::phrase('cc_opt_out_explain'),
				),
				'required' => array(
					// (always required)
					'title' => \XF::phrase('cc_required'),
					// This application is always required
					'description' => \XF::phrase('cc_required_explain'),
				),
				// Purposes
				'purposes' => \XF::phrase('cc_purposes'),
				// Purpose
				'purpose' => \XF::phrase('cc_purpose'),
			),
			// Powered by Klaro and Luehrsen // Heinrich
			'poweredBy' => \XF::phrase('cc_powered_by'),
		);

		// Collect all the app information
		foreach( $apps as $app ) {
			$app_purposes = array();

			foreach( $purposes as $purpose ) {
				if ( in_array( $purpose->purpose_id, $app->purposes ) ) {
					$app_purposes[] = $purpose->name;
				}
			}

			$cookies = explode(PHP_EOL, $app->cookies);

			$apps_array[] = array(
				'name' => $app->name,
				'title' => $app->title,
				'required' => $app->required,
				'optOut' => $app->optOut,
				'onlyOnce' => $app->onlyOnce,
				'purposes' => $app_purposes,
				'description' => $app->description,
				'cookies' => $cookies,
				'callback' => $app->callback,
			);
		}

		foreach( $purposes as $purpose ) {
			$translations['purposes'][$purpose->name] = $purpose->title;
		}

		$config = array(
			'storageMethod'          => \XF::options()->ccStorageMethod,
			'cookieName'             => \XF::options()->ccCookieName,
			'cookieExpiresAfterDays' => intval(\XF::options()->ccCookieExpires),
			'privacyPolicy'          => \XF::app()->container('privacyPolicyUrl'),
			'default'                => \XF::options()->ccDefaultState ? true : false,
			'mustConsent'            => \XF::options()->ccMustConsent ? true : false,
			'acceptAll'              => \XF::options()->ccAcceptAll ? true : false,
			'hideDeclineAll'         => \XF::options()->ccDeclineAll ? false : true,
			'lang'                   => 'xf',
			'translations'					 => array(
				'xf' => $translations
			),
			'apps'                   => $apps_array,
			'poweredBy'              => 'https://www.luehrsen-heinrich.de',
		);

		$html = '<xf:js dev="lh/cookieconsent/klaro.bundle.js" addon="LH/CookieConsent"></xf:js>';
		$html .= '<script type="text/javascript"> window.cookieConsentConfig = ' . json_encode($config) . ';</script>';

		return $html;
	}
}
