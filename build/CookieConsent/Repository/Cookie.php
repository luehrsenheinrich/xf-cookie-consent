<?php

namespace LH\CookieConsent\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class Cookie extends Repository
{
	/**
	 * @return Finder
	 */
	public function findCookiesForList()
	{
		$cookieFinder = $this->finder('LH\CookieConsent:Cookie');
		return $cookieFinder->order(['cookie_id']);
	}
}
