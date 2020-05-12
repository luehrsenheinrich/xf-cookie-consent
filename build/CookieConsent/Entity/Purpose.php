<?php

namespace LH\CookieConsent\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Purpose extends Entity
{
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_lh_cookie_purpose';
		$structure->shortName = 'LH\CookieConsent:Purpose';
		$structure->primaryKey = 'purpose_id';
		$structure->columns = [
			'purpose_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'name' => [
				'type' => self::STR,
				'maxLength' => 50,
				'required' => 'please_enter_valid_name',
				'unique' => true,
			],
			'title' => [
				'type' => self::STR,
				'maxLength' => 50,
				'required' => 'please_enter_valid_title',
			]
		];

		return $structure;
	}
}
