<?php

namespace LH\CookieConsent;

class CookieConsent {
	public static function getCookieConsentConfigObject()
	{
		$config = array(
			'storageMethod'          => \XF::options()->ccStorageMethod,
			'cookieName'             => \XF::options()->ccCookieName,
			'cookieExpiresAfterDays' => intval(\XF::options()->ccCookieExpires),
			'privacyPolicy'          => \XF::app()->container('privacyPolicyUrl'),
			'default'                => \XF::options()->ccDefaultState ? true : false,
			'mustConsent'            => \XF::options()->ccMustConsent ? true : false,
			'acceptAll'              => \XF::options()->ccAcceptAll ? true : false,
			'hideDeclineAll'         => \XF::options()->ccDeclineAll ? false : true,
			'translations'					 => array(
				'en' => array(
					'poweredBy' => (string) time(),
				),
			),
			'apps'                   => array(),
			'poweredBy'              => 'test',
		);

		$html = '<script type="text/javascript"> window.klaroConfig = ' . json_encode($config) . ';</script>';

		return $html;
	}
}
