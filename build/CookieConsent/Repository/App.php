<?php

namespace LH\CookieConsent\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class App extends Repository
{
	/**
	 * @return Finder
	 */
	public function findAppsForList()
	{
		$appFinder = $this->finder('LH\CookieConsent:App');
		return $appFinder->order(['displayOrder'], 'ASC');
	}
}
