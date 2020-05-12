<?php

namespace LH\CookieConsent\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class Purpose extends Repository
{
	/**
	 * @return Finder
	 */
	public function findPurposesForList()
	{
		$purposeFinder = $this->finder('LH\CookieConsent:Purpose');
		return $purposeFinder->order(['purpose_id']);
	}
}
