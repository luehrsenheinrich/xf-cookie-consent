<?php

namespace LH\CookieConsent;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

class Setup extends \XF\AddOn\AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	public function installStep1()
	{
		$this->schemaManager()->createTable('xf_lh_cookie_app', function (Create $table) {
				$table->addColumn('app_id', 'int')->autoIncrement();
				$table->addColumn('title', 'varchar', 50);
				$table->addColumn('name', 'varchar', 50);
				$table->addColumn('description', 'varchar', 255);
				$table->addColumn('callback', 'text')->setDefault(null);
				$table->addColumn('cookies', 'text')->setDefault(null);
				$table->addColumn('purposes', 'mediumblob')->comment('JSON data');

				//Boolean states
				$table->addColumn('active', 'tinyint')->setDefault(1);
				$table->addColumn('required', 'tinyint')->setDefault(0);
				$table->addColumn('optOut', 'tinyint')->setDefault(0);
				$table->addColumn('onlyOnce', 'tinyint')->setDefault(0);
		});

		$this->schemaManager()->createTable('xf_lh_cookie_purpose', function (Create $table) {
				$table->addColumn('purpose_id', 'int')->autoIncrement();
				$table->addColumn('title', 'varchar', 50);
				$table->addColumn('name', 'varchar', 50);
		});
	}

	public function uninstallStep1()
	{
		$this->schemaManager()->dropTable('xf_lh_cookie_app');
		$this->schemaManager()->dropTable('xf_lh_cookie_purpose');
	}
}
