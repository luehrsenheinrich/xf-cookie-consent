<?php

namespace LH\CookieConsent\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class App extends Entity
{
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_lh_cookie_app';
		$structure->shortName = 'LH\CookieConsent:App';
		$structure->primaryKey = 'app_id';
		$structure->columns = [
			'app_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
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
			],
			'description' => [
				'type' => self::STR,
				'maxLength' => 255,
			],
			'displayOrder' => ['type' => self::UINT, 'default' => 10],
			'purposes' => ['type' => self::JSON_ARRAY, 'default' => []],
			'cookies' => ['type' => self::STR, 'default' => ''],
			'callback' => ['type' => self::STR, 'default' => ''],
			'active' => ['type' => self::BOOL, 'default' => true],
			'required' => ['type' => self::BOOL, 'default' => false],
			'optOut' => ['type' => self::BOOL, 'default' => false],
			'onlyOnce' => ['type' => self::BOOL, 'default' => false],
		];

		return $structure;
	}
}
