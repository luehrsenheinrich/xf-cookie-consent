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
				$table->addColumn('purposes', 'longtext')->comment('JSON data');
				$table->addColumn('displayOrder', 'int')->setDefault(10);

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

	public function installStep2() {
		$default_purposes = array(
			'analytics' => \XF::phrase( 'cc_defaults_purpose_analytics' ),
			'board' => \XF::phrase( 'cc_defaults_purpose_board' ),
		);


		$p_entities = array();
		foreach( $default_purposes as $name => $title ) {
			$purpose = \XF::em()->create('LH\CookieConsent:Purpose');
			$purpose->name = $name;
			$purpose->title = $title;

			$purpose->save();

			$p_entities[$name] = $purpose;
		}

		// Add Google Analtics
		$ga_app = \XF::em()->create('LH\CookieConsent:App');
		$ga_app->name = 'google_analytics';
		$ga_app->title = \XF::phrase( 'cc_defaults_google_analytics_title' );
		$ga_app->description = \XF::phrase( 'cc_defaults_google_analytics_description' );
		$ga_app->cookies = "_ga\r\n_gid\r\n_gat";
		$ga_app->purposes = array( $p_entities['analytics']->purpose_id );

		$ga_app->save();

		// Add Xenforo
		$xf_app = \XF::em()->create('LH\CookieConsent:App');
		$xf_app->name = 'xenforo';
		$xf_app->title = \XF::phrase( 'cc_defaults_xenforo_title' );
		$xf_app->description = \XF::phrase( 'cc_defaults_xenforo_description' );
		$xf_app->purposes = array( $p_entities['board']->purpose_id );
		$xf_app->displayOrder = 999;
		$xf_app->required = 1;

		$xf_app->save();
	}

	public function uninstallStep1()
	{
		$this->schemaManager()->dropTable('xf_lh_cookie_app');
		$this->schemaManager()->dropTable('xf_lh_cookie_purpose');
	}
}
