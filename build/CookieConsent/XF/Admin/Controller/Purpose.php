<?php

namespace LH\CookieConsent\XF\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class Purpose extends AbstractController
{
	public function actionIndex()
	{

		$purposes = $this->getPurposeRepo()->findPurposesForList();
		$viewParams = [
			'purposes' => $purposes->fetch(),
		];

		return $this->view('LH\CookieConsent:Purpose\Listing', 'purpose_list', $viewParams);
	}

	public function purposeAddEdit(\LH\CookieConsent\Entity\Purpose $purpose)
	{
		$viewParams = [
			'purpose' => $purpose
		];
		return $this->view('LH\CookieConsent:Purpose\Edit', 'purpose_edit', $viewParams);
	}

	public function actionEdit(ParameterBag $params)
	{
		$purpose = $this->assertPurposeExists($params['purpose_id']);
		return $this->purposeAddEdit($purpose);
	}

	public function actionAdd()
	{
		$purpose = $this->em()->create('LH\CookieConsent:Purpose');
		return $this->purposeAddEdit($purpose);
	}

	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();

		if ($params['purpose_id'])
		{
			$purpose = $this->assertPurposeExists($params['purpose_id']);
		}
		else
		{
			$purpose = $this->em()->create('LH\CookieConsent:Purpose');
		}

		$this->purposeSaveProcess($purpose)->run();

		return $this->redirect($this->buildLink('cookie-consent/purposes'));
	}

	public function actionDelete(ParameterBag $params)
	{
		$purpose = $this->assertPurposeExists($params['purpose_id']);

		/** @var \XF\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('XF:Delete');
		return $plugin->actionDelete(
			$purpose,
			$this->buildLink('cookie-consent/purposes/delete', $purpose),
			$this->buildLink('cookie-consent/purposes/edit', $purpose),
			$this->buildLink('cookie-consent/purposes'),
			$purpose->title
		);
	}

	public function actionToggle()
	{
		/** @var \XF\ControllerPlugin\Toggle $plugin */
		$plugin = $this->plugin('XF:Toggle');
		return $plugin->actionToggle('LH\CookieConsent:Purpose');
	}

	/**
	 * @return LH\Repository\Purpose
	 */
	protected function getPurposeRepo()
	{
		return $this->repository('LH\CookieConsent:Purpose');
	}

	protected function purposeSaveProcess(\LH\CookieConsent\Entity\Purpose $purpose)
	{
		$entityInput = $this->filter([
			'title' => 'str',
			'name' => 'str',
		]);

		$form = $this->formAction();
		$form->basicEntitySave($purpose, $entityInput);

		return $form;
	}

	/**
	 * @param string $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \LH\CookieConsent\Entity\Purpose
	 */
	protected function assertPurposeExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('LH\CookieConsent:Purpose', $id, $with, $phraseKey);
	}
}
