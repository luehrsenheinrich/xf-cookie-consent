<?php

namespace LH\CookieConsent\XF\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class Cookie extends AbstractController
{
	public function actionIndex()
	{

		$cookies = $this->getCookieRepo()->findCookiesForList();
		$viewParams = [
			'cookies' => $cookies->fetch(),
			'exportView' => $this->filter('export', 'bool')
		];

		return $this->view('LH\CookieConsent:Cookie\Listing', 'cookie_list', $viewParams);
	}

	public function cookieAddEdit(\LH\CookieConsent\Entity\Cookie $cookie)
	{
		$viewParams = [
			'cookie' => $cookie
		];
		return $this->view('LH\CookieConsent:Cookie\Edit', 'cookie_edit', $viewParams);
	}

	public function actionEdit(ParameterBag $params)
	{
		$cookie = $this->assertCookieExists($params['cookie_id']);
		return $this->cookieAddEdit($cookie);
	}

	public function actionAdd()
	{
		$cookie = $this->em()->create('LH\CookieConsent:Cookie');
		return $this->cookieAddEdit($cookie);
	}

	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();

		if ($params['cookie_id'])
		{
			$cookie = $this->assertCookieExists($params['cookie_id']);
		}
		else
		{
			$cookie = $this->em()->create('LH\CookieConsent:Cookie');
		}

		$this->cookieSaveProcess($cookie)->run();

		return $this->redirect($this->buildLink('cookies/edit', $cookie));
	}

	public function actionDelete(ParameterBag $params)
	{
		$cookie = $this->assertCookieExists($params['cookie_id']);

		/** @var \XF\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('XF:Delete');
		return $plugin->actionDelete(
			$cookie,
			$this->buildLink('cookies/delete', $cookie),
			$this->buildLink('cookies/edit', $cookie),
			$this->buildLink('cookies'),
			$cookie->title
		);
	}

	public function actionToggle()
	{
		/** @var \XF\ControllerPlugin\Toggle $plugin */
		$plugin = $this->plugin('XF:Toggle');
		return $plugin->actionToggle('LH\CookieConsent:Cookie');
	}

	/**
	 * @return LH\Repository\Cookie
	 */
	protected function getCookieRepo()
	{
		return $this->repository('LH\CookieConsent:Cookie');
	}

	protected function cookieSaveProcess(\LH\CookieConsent\Entity\Cookie $cookie)
	{
		$entityInput = $this->filter([
			'title' => 'str',
			'name' => 'str',
			'callback' => 'str,no-trim',
			'cookies' => 'str,no-trim',
			'active' => 'bool',
			'required' => 'bool',
			'optOut' => 'bool',
			'onlyOnce' => 'bool',
		]);

		$form = $this->formAction();
		$form->basicEntitySave($cookie, $entityInput);

		return $form;
	}

	/**
	 * @param string $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \XF\Entity\BbCode
	 */
	protected function assertCookieExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('LH\CookieConsent:Cookie', $id, $with, $phraseKey);
	}
}
