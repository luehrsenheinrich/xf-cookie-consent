<?php

namespace LH\CookieConsent\XF\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class App extends AbstractController
{
	public function actionIndex()
	{

		$viewParams = [
			'apps' => $this->getAppRepo()->findAppsForList()->fetch(),
		];

		return $this->view('LH\CookieConsent:App\Listing', 'app_list', $viewParams);
	}

	public function appAddEdit(\LH\CookieConsent\Entity\App $app)
	{

		$viewParams = [
			'app' => $app,
			'purposes' => $this->getPurposeRepo()->findPurposesForList()->fetch(),
		];
		return $this->view('LH\CookieConsent:App\Edit', 'app_edit', $viewParams);
	}

	public function actionEdit(ParameterBag $params)
	{
		$app = $this->assertAppExists($params['app_id']);
		return $this->appAddEdit($app);
	}

	public function actionAdd()
	{
		$app = $this->em()->create('LH\CookieConsent:App');
		return $this->appAddEdit($app);
	}

	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();

		if ($params['app_id'])
		{
			$app = $this->assertAppExists($params['app_id']);
		}
		else
		{
			$app = $this->em()->create('LH\CookieConsent:App');
		}

		$this->appSaveProcess($app)->run();

		return $this->redirect($this->buildLink('cookie-consent/apps/edit', $app));
	}

	public function actionDelete(ParameterBag $params)
	{
		$app = $this->assertAppExists($params['app_id']);

		/** @var \XF\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('XF:Delete');
		return $plugin->actionDelete(
			$app,
			$this->buildLink('cookie-consent/apps/delete', $app),
			$this->buildLink('cookie-consent/apps/edit', $app),
			$this->buildLink('cookie-consent/apps'),
			$app->title
		);
	}

	public function actionToggle()
	{
		/** @var \XF\ControllerPlugin\Toggle $plugin */
		$plugin = $this->plugin('XF:Toggle');
		return $plugin->actionToggle('LH\CookieConsent:App');
	}

	/**
	 * @return LH\Repository\App
	 */
	protected function getAppRepo()
	{
		return $this->repository('LH\CookieConsent:App');
	}

	/**
	 * @return LH\Repository\Purpose
	 */
	protected function getPurposeRepo()
	{
		return $this->repository('LH\CookieConsent:Purpose');
	}

	protected function appSaveProcess(\LH\CookieConsent\Entity\App $app)
	{
		$entityInput = $this->filter([
			'title' => 'str',
			'name' => 'str',
			'description' => 'str',
			'callback' => 'str,no-trim',
			'cookies' => 'str,no-trim',
			'purposes' => 'json-array',
			'active' => 'bool',
			'required' => 'bool',
			'optOut' => 'bool',
			'onlyOnce' => 'bool',
			'displayOrder' => 'uint',
		]);

		$form = $this->formAction();
		$form->basicEntitySave($app, $entityInput);

		return $form;
	}

	/**
	 * @param string $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \LH\CookieConsent\Entity\App
	 */
	protected function assertAppExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('LH\CookieConsent:App', $id, $with, $phraseKey);
	}
}
